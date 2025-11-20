<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
     * Crée un nouvel utilisateur dans la base de données
     * Convertit les données du formulaire vers la structure de la BDD
     */
    public function store(Request $request): JsonResponse
    {
        // Validation des données reçues du formulaire
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:Administrateur,Employe,Entreprise Support',
            'company' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'entreprise_id' => 'nullable|exists:entreprise,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Conversion du rôle texte vers le type numérique de la BDD
            $typeMap = [
                'Administrateur' => 0,        // Administrateur = type 0
                'Employe' => 1,               // Employé = type 1
                'Entreprise Support' => 2     // Support Entreprise = type 2
            ];

            // Création de l'utilisateur avec les champs de la BDD
            $user = User::create([
                'nom' => $request->name,                    // nom au lieu de name
                'type' => $typeMap[$request->role],         // type numérique au lieu de role
                'code_entreprise' => $request->company,     // code_entreprise au lieu de company
                'entreprise_id' => $request->entreprise_id, // ID de l'entreprise
                'username' => strtolower(str_replace(' ', '.', $request->name)), // génération automatique du username
                'matricule' => 'USR' . time(),              // génération automatique du matricule
                'password' => Hash::make($request->password), // mot de passe fourni par l'utilisateur
            ]);

            // Retour des données formatées pour l'interface
            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->nom,
                    'role' => $request->role,
                    'company' => $user->code_entreprise,
                    'entreprise_id' => $user->entreprise_id,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
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
     * Met à jour un utilisateur existant
     * Convertit les données du formulaire vers la structure de la BDD
     */
    public function update(Request $request, User $user): JsonResponse
    {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:Administrateur,Employe,Entreprise Support',
            'company' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'entreprise_id' => 'nullable|exists:entreprise,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Conversion du rôle texte vers le type numérique
            $typeMap = [
                'Administrateur' => 0,        // Administrateur = type 0
                'Employe' => 1,               // Employé = type 1
                'Entreprise Support' => 2     // Support Entreprise = type 2
            ];

            // Préparation des données à mettre à jour avec les vrais champs de la BDD
            $updateData = [
                'nom' => $request->name,                    // nom au lieu de name
                'type' => $typeMap[$request->role],         // type numérique au lieu de role
                'code_entreprise' => $request->company,     // code_entreprise au lieu de company
                'entreprise_id' => $request->entreprise_id, // ID de l'entreprise
            ];

            // Ajouter le mot de passe seulement s'il est fourni
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Mise à jour de l'utilisateur
            $user->update($updateData);

            // Retour des données formatées pour l'interface
            return response()->json([
                'message' => 'Utilisateur mis à jour avec succès',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->nom,
                    'role' => $request->role,
                    'company' => $user->code_entreprise,
                    'entreprise_id' => $user->entreprise_id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            // Prevent deletion of the current authenticated user
            if (auth()->id() === $user->id) {
                return response()->json([
                    'message' => 'Vous ne pouvez pas supprimer votre propre compte'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'message' => 'Utilisateur supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
