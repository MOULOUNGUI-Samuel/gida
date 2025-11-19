<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Demande;
use Illuminate\Support\Facades\Log;

class NouvelleDemandeController extends Controller
{
    /**
     * Affiche le formulaire de création de demande
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour créer une demande.');
        }

        $user = Auth::user()->load('entreprise');

        return view('demandes.create', compact('user'));
    }

    /**
     * Traite la création d'une demande
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'categorie' => 'required|string',
                'priorite' => 'required|string',
                'description' => 'required|string',
                'date_limite' => 'nullable|date',
                'fichier' => 'nullable|file|max:10240',
                'infos_supplementaires' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirection avec les messages d'erreurs de validation
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.');
        }

        try {
            $user = Auth::user();

            // Formatage de la date_limite si elle existe
            if (!empty($validated['date_limite'])) {
                $validated['date_limite'] = \Carbon\Carbon::parse($validated['date_limite'])->format('Y-m-d');
            }

            // Ajout des infos automatiques
            $validated['nom'] = $user->nom;
            $validated['fonction'] = $user->fonction ?? 'Non spécifiée';
            $validated['societe'] = $user->societe ?? 'Non spécifiée';
            $validated['mail'] = $user->email ?? 'non-specifie@example.com';
            $validated['user_id'] = $user->id;
            $validated['statut'] = 'en_attente';

            // Création de la demande
            $demande = Demande::create($validated);

            // Gestion du fichier joint après la création de la demande
            if ($request->hasFile('fichier')) {
                $file = $request->file('fichier');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('pieces_jointes', $fileName, 'public');
                
                // Enregistrement dans la table piece_jointes
                $pieceJointe = new \App\Models\PieceJointe([
                    'demande_id' => $demande->id,
                    'nom_fichier' => $file->getClientOriginalName(),
                    'chemin' => $filePath,
                    'type_mime' => $file->getMimeType(),
                    'taille' => $file->getSize(),
                    'extension' => $file->getClientOriginalExtension(),
                ]);
                $pieceJointe->save();
            }

            // Redirection vers le formulaire avec message de succès
            return redirect()
                ->route('demandes.create')
                ->with('success', '🎉 Votre demande a été créée avec succès ! Numéro de suivi : ' . $demande->id);

        } catch (\Exception $e) {
            Log::error('Erreur création demande: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la demande. Veuillez réessayer.');
        }
    }

    /**
     * Tableau de bord
     */
    public function dashboard()
    {
        try {
            $userId = Auth::id();
            $demandes = Demande::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard', compact('demandes'));
        } catch (\Exception $e) {
            return redirect()->route('dashboardEmployer')
                ->with('error', 'Impossible de charger vos demandes.');
        }
    }

    /**
     * Affiche une demande
     */
    public function show($id)
    {
        try {
            $demande = Demande::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return view('demandes.show', compact('demande'));
        } catch (\Exception $e) {
            return redirect()->route('dashboardEmployer')
                ->with('error', 'Demande introuvable ou accès refusé.');
        }
    }

    /**
     * Historique (terminées uniquement)
     */
    public function historique()
    {
        try {
            $demandes = Demande::where('user_id', Auth::id())
                ->where('statut', 'terminé')
                ->orderBy('updated_at', 'desc')
                ->get();

            return view('demandes.historique', compact('demandes'));
        } catch (\Exception $e) {
            return redirect()->route('dashboardEmployer')
                ->with('error', 'Impossible de récupérer l\'historique.');
        }
    }
}
