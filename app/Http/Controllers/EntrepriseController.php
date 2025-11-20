<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:entreprise,code|max:50',
            'nom' => 'required|string|max:255',
            'matricule' => 'nullable|string|max:100',
            'societe' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:500',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $entreprise = Entreprise::create([
            'code' => $request->code,
            'nom' => $request->nom,
            'matricule' => $request->matricule,
            'societe' => $request->societe,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'description' => $request->description,
            'active' => $request->has('active') ? true : false,
        ]);

        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise créée avec succès.');
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:entreprise,code,' . $entreprise->id,
            'nom' => 'required|string|max:255',
            'matricule' => 'nullable|string|max:100',
            'societe' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:500',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $entreprise->update([
            'code' => $request->code,
            'nom' => $request->nom,
            'matricule' => $request->matricule,
            'societe' => $request->societe,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'description' => $request->description,
            'active' => $request->has('active') ? true : false,
        ]);

        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise mise à jour avec succès.');
    }

    /**
     * Remove the specified entreprise from storage.
     */
    public function destroy(Entreprise $entreprise)
    {
        // Vérifier si l'entreprise a des utilisateurs
        if ($entreprise->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer une entreprise qui a des utilisateurs associés.');
        }

        $entreprise->delete();

        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise supprimée avec succès.');
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
            'user_ids' => 'required|array',
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
    public function detachUser(Request $request, Entreprise $entreprise, User $user)
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
            'active' => !$entreprise->active
        ]);

        $status = $entreprise->active ? 'activée' : 'désactivée';

        return redirect()->back()
            ->with('success', "Entreprise {$status} avec succès.");
    }
}
