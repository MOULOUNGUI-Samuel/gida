<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the entreprises.
     */
    public function index()
    {
        $entreprises = Entreprise::withCount('users')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('entreprises.index', compact('entreprises'));
    }

    /**
     * API: Get all entreprises as JSON
     */
    public function apiIndex()
    {
        try {
            $entreprises = Entreprise::select('id', 'nom', 'societe', 'code')
                ->where('active', true)
                ->orderBy('nom', 'asc')
                ->get();

            return response()->json($entreprises);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du chargement des entreprises',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new entreprise.
     */
    public function create()
    {
        return view('entreprises.create');
    }

    /**
     * Store a newly created entreprise in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // ✅ table corrigée : entreprises
                'code'       => 'required|string|unique:entreprises,code|max:50',
                'nom'        => 'required|string|max:255',
                'matricule'  => 'nullable|string|max:100',
                'societe'    => 'nullable|string|max:255',
                'adresse'    => 'nullable|string|max:500',
                'telephone'  => 'nullable|string|max:20',
                'email'      => 'nullable|email|max:255',
                // ✅ ajout max pour être cohérent avec le message d'erreur
                'description'=> 'nullable|string|max:255',
                'logo'       => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'active'     => 'nullable|boolean',
            ],
            [
                'code.required'     => 'Le code est obligatoire.',
                'code.unique'       => 'Le code doit être unique.',
                'code.max'          => 'Le code doit contenir au maximum 50 caractères.',
                'nom.required'      => 'Le nom est obligatoire.',
                'nom.max'           => 'Le nom doit contenir au maximum 255 caractères.',
                'matricule.max'     => 'Le matricule doit contenir au maximum 100 caractères.',
                'societe.max'       => 'Le nom de la société doit contenir au maximum 255 caractères.',
                'adresse.max'       => 'L\'adresse doit contenir au maximum 500 caractères.',
                'telephone.max'     => 'Le numéro de téléphone doit contenir au maximum 20 caractères.',
                'email.max'         => 'L\'email doit contenir au maximum 255 caractères.',
                'description.max'   => 'La description doit contenir au maximum 255 caractères.',
                'logo.image'        => 'Le fichier doit être une image.',
                'logo.mimes'        => 'Le logo doit être au format JPEG, JPG, PNG ou GIF.',
                'logo.max'          => 'Le logo ne doit pas dépasser 2 Mo.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Gérer l'upload du logo
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            Entreprise::create([
                'code'        => $request->code,
                'nom'         => $request->nom,
                'matricule'   => $request->matricule,
                'societe'     => $request->societe,
                'adresse'     => $request->adresse,
                'telephone'   => $request->telephone,
                'email'       => $request->email,
                'description' => $request->description,
                'logo'        => $logoPath,
                'active'      => $request->has('active') ? 1 : 0,
            ]);

            return redirect()->route('entreprises.index')
                ->with('success', 'Entreprise créée avec succès.');
        } catch (\Exception $e) {
            // En cas d'erreur, supprimer le logo si uploadé
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la création de l\'entreprise : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified entreprise.
     */
    public function show(Entreprise $entreprise)
    {
        $entreprise->load('users', 'demandes');

        return view('entreprises.show', compact('entreprise'));
    }

    /**
     * Show the form for editing the specified entreprise.
     */
    public function edit(Entreprise $entreprise)
    {
        return view('entreprises.edit', compact('entreprise'));
    }

    /**
     * Update the specified entreprise in storage.
     */
    public function update(Request $request, Entreprise $entreprise)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // ✅ table corrigée + ignore l'ID actuel
                'code'       => 'required|string|max:50|unique:entreprises,code,' . $entreprise->id,
                'nom'        => 'required|string|max:255',
                'matricule'  => 'nullable|string|max:100',
                'societe'    => 'nullable|string|max:255',
                'adresse'    => 'nullable|string|max:500',
                'telephone'  => 'nullable|string|max:20',
                'email'      => 'nullable|email|max:255',
                'description'=> 'nullable|string|max:255',
                'logo'       => 'nullable',
                'active'     => 'nullable|boolean',
            ],
            [
                'code.required'     => 'Le code est obligatoire.',
                'code.unique'       => 'Le code doit être unique.',
                'code.max'          => 'Le code doit contenir au maximum 50 caractères.',
                'nom.required'      => 'Le nom est obligatoire.',
                'nom.max'           => 'Le nom doit contenir au maximum 255 caractères.',
                'matricule.max'     => 'Le matricule doit contenir au maximum 100 caractères.',
                'societe.max'       => 'Le nom de la société doit contenir au maximum 255 caractères.',
                'adresse.max'       => 'L\'adresse doit contenir au maximum 500 caractères.',
                'telephone.max'     => 'Le numéro de téléphone doit contenir au maximum 20 caractères.',
                'email.max'         => 'L\'email doit contenir au maximum 255 caractères.',
                'description.max'   => 'La description doit contenir au maximum 255 caractères.',
                'logo'              => 'nullable',
                'active'     => 'nullable|boolean',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'code'        => $request->code,
                'nom'         => $request->nom,
                'matricule'   => $request->matricule,
                'societe'     => $request->societe,
                'adresse'     => $request->adresse,
                'telephone'   => $request->telephone,
                'email'       => $request->email,
                'description' => $request->description,
                'active'      => $request->has('active') ? 1 : 0,
            ];

            $oldLogo = $entreprise->logo;
            $newLogoPath = null;

            // Gérer l'upload du nouveau logo
            if ($request->hasFile('logo')) {
                // Stocker le nouveau logo
                $newLogoPath = $request->file('logo')->store('logos', 'public');
                $updateData['logo'] = $newLogoPath;
            }

            // Mettre à jour l'entreprise
            $entreprise->update($updateData);

            // Supprimer l'ancien logo seulement après la mise à jour réussie
            if ($newLogoPath && $oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            return redirect()->route('entreprises.index')
                ->with('success', 'Entreprise mise à jour avec succès.');
        } catch (\Exception $e) {
            // En cas d'erreur, supprimer le nouveau logo si uploadé
            if (isset($newLogoPath) && Storage::disk('public')->exists($newLogoPath)) {
                Storage::disk('public')->delete($newLogoPath);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de l\'entreprise : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified entreprise from storage.
     */
    public function destroy(Entreprise $entreprise)
    {
        try {
            // Vérifier si l'entreprise a des utilisateurs
            if ($entreprise->users()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer une entreprise qui a des utilisateurs associés.');
            }

            // Vérifier si l'entreprise a des demandes
            if ($entreprise->demandes()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer une entreprise qui a des demandes associées.');
            }

            $logo = $entreprise->logo;

            // Supprimer l'entreprise
            $entreprise->delete();

            // Supprimer le logo après la suppression réussie
            if ($logo) {
                Storage::disk('public')->delete($logo);
            }

            return redirect()->route('entreprises.index')
                ->with('success', 'Entreprise supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'entreprise : ' . $e->getMessage());
        }
    }

    /**
     * Show the form for adding users to an entreprise.
     */
    public function addUsers(Entreprise $entreprise)
    {
        // Récupérer tous les utilisateurs qui n'ont pas d'entreprise
        $availableUsers = User::whereNull('entreprise_id')
            ->orWhere('entreprise_id', $entreprise->id)
            ->get();

        $entrepriseUsers = $entreprise->users;

        return view('entreprises.add-users', compact('entreprise', 'availableUsers', 'entrepriseUsers'));
    }

    /**
     * Attach users to an entreprise.
     */
    public function attachUsers(Request $request, Entreprise $entreprise)
    {
        $validator = Validator::make($request->all(), [
            'user_ids'   => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Attacher les utilisateurs à l'entreprise
        User::whereIn('id', $request->user_ids)
            ->update(['entreprise_id' => $entreprise->id]);

        return redirect()->route('entreprises.show', $entreprise->id)
            ->with('success', 'Utilisateurs ajoutés à l\'entreprise avec succès.');
    }

    /**
     * Detach a user from an entreprise.
     */
    public function detachUser(Entreprise $entreprise, User $user)
    {
        if ($user->entreprise_id != $entreprise->id) {
            return redirect()->back()
                ->with('error', 'Cet utilisateur n\'appartient pas à cette entreprise.');
        }

        $user->update(['entreprise_id' => null]);

        return redirect()->back()
            ->with('success', 'Utilisateur retiré de l\'entreprise avec succès.');
    }

    /**
     * Toggle entreprise active status.
     */
    public function toggleActive(Entreprise $entreprise)
    {
        $entreprise->update([
            'active' => !$entreprise->active,
        ]);

        $status = $entreprise->active ? 'activée' : 'désactivée';

        return redirect()->back()
            ->with('success', "Entreprise {$status} avec succès.");
    }
}
