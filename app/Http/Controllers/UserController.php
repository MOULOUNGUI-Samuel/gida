<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Affiche la liste de tous les utilisateurs
     * Récupère les utilisateurs depuis la base de données et les formate pour l'affichage
     */
    public function index(): JsonResponse
    {
        try {
            // Récupération des utilisateurs avec mapping des champs de la BDD vers l'interface
            $users = User::with('entreprise')
                ->select('id', 'nom as name', 'type', 'code_entreprise as company', 'entreprise_id')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($user) {
                    // Conversion du type numérique vers un rôle lisible
                    $roleMap = [
                        0 => 'Administrateur',      // Type 0 = Administrateur
                        1 => 'Employe',             // Type 1 = Employé
                        2 => 'Entreprise Support'   // Type 2 = Support Entreprise
                    ];

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'role' => $roleMap[$user->type] ?? 'Employe',
                        'company' => $user->company ?? 'Non définie',
                        'entreprise_id' => $user->entreprise_id,
                        'entreprise_nom' => $user->entreprise ? $user->entreprise->nom : null
                    ];
                });

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du chargement des utilisateurs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur
     */
    public function create()
    {
        $entreprises = Entreprise::where('active', true)
            ->orderBy('nom', 'asc')
            ->get();

        return view('admin.users.create', compact('entreprises'));
    }

    /**
     * Crée un nouvel utilisateur dans la base de données.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'          => 'required|string|max:255',
                'role'          => 'required|string|in:Administrateur,Employe,Entreprise Support',
                'company'       => 'required|string|max:255',
                'password'      => 'required|string|min:6',
                'entreprise_id' => 'nullable|exists:entreprises,id',
            ],
            [
                'name.required'          => 'Le nom est obligatoire.',
                'role.required'          => 'Le rôle est obligatoire.',
                'role.in'                => 'Le rôle doit être Administrateur, Employe ou Entreprise Support.',
                'company.required'       => 'Le code société est obligatoire.',
                'company.max'            => 'Le code société doit contenir au maximum 255 caractères.',
                'password.required'      => 'Le mot de passe est obligatoire.',
                'password.min'           => 'Le mot de passe doit contenir au minimum 6 caractères.',
                'entreprise_id.exists'   => 'L\'entreprise sélectionnée n\'existe pas.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Conversion rôle texte -> type numérique
            $typeMap = [
                'Administrateur'     => 0,
                'Employe'            => 1,
                'Entreprise Support' => 2,
            ];

            $user = User::create([
                'nom'            => $request->name,
                'type'           => $typeMap[$request->role] ?? 1,
                'code_entreprise'=> $request->company,
                'entreprise_id'  => $request->entreprise_id,
                'societe'          => $request->company,
                'email'           => $request->email,
                'username'       => strtolower(str_replace(' ', '.', $request->name)),
                'matricule'      => 'USR' . time(),
                'password'       => Hash::make($request->password),
            ]);

            return redirect()
                ->route('users')
                ->with('success', 'Utilisateur créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Affiche les détails d'un utilisateur spécifique
     * Formate les données de la BDD pour l'interface
     */
    public function show(User $user): JsonResponse
    {
        // Conversion du type numérique vers un rôle lisible
        $roleMap = [
            0 => 'Admin',
            1 => 'Gestionnaire',
            2 => 'employe'
        ];

        return response()->json([
            'id' => $user->id,
            'name' => $user->nom,                               // nom depuis la BDD
            'role' => $roleMap[$user->type] ?? 'employe',       // conversion type -> role
            'company' => $user->code_entreprise ?? 'Non définie', // code_entreprise depuis la BDD
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur
     */
    public function edit(User $user)
    {
        $entreprises = Entreprise::where('active', true)
            ->orderBy('nom', 'asc')
            ->get();

        return view('admin.users.edit', compact('user', 'entreprises'));
    }

    /**
     * Met à jour un utilisateur existant.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'          => 'required|string|max:255',
                'role'          => 'required|string|in:Administrateur,Employe,Entreprise Support',
                'company'       => 'required|string|max:255',
                'email'          => 'required|email',
                'password'      => 'nullable|string|min:6',
                'entreprise_id' => 'nullable|exists:entreprises,id',
            ],
            [
                'name.required'          => 'Le nom est obligatoire.',
                'role.required'          => 'Le rôle est obligatoire.',
                'role.in'                => 'Le rôle doit être Administrateur, Employe ou Entreprise Support.',
                'company.required'       => 'Le code société est obligatoire.',
                'company.max'            => 'Le code société doit contenir au maximum 255 caractères.',
                'email.required'          => 'L\'email est obligatoire.',

                'email.email'             => 'L\'email doit être une adresse email valide.',
                'password.min'           => 'Le mot de passe doit contenir au minimum 6 caractères.',
                'entreprise_id.exists'   => 'L\'entreprise sélectionnée n\'existe pas.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $typeMap = [
                'Administrateur'     => 0,
                'Employe'            => 1,
                'Entreprise Support' => 2,
            ];

            $updateData = [
                'nom'            => $request->name,
                'type'           => $typeMap[$request->role] ?? 1,
                'email'          => $request->email,
                'code_entreprise'=> $request->company,
                'entreprise_id'  => $request->entreprise_id,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return redirect()
                ->route('users')
                ->with('success', 'Utilisateur mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de l\'utilisateur : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deletion of the current authenticated user
            if (Auth::id() === $user->id) {
                return redirect()->back()
                    ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            }

            $user->delete();

            return redirect()->route('users')
                ->with('success', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'utilisateur : ' . $e->getMessage());
        }
    }
}
