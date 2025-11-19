<?php

namespace App\Http\Controllers;

use App\Models\Demandes;
use App\Http\Requests\StoreDemandeRequest;
use App\Http\Requests\UpdateDemandeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\DemandeUpdated; // ⚡ import correct
use Illuminate\Support\Facades\Storage;
use App\Models\PieceJointe;

class DemandesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $demandes = Demandes::with('user')->orderBy('created_at', 'desc')->get();
        return view('demandes.index', compact('demandes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('demandes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDemandeRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        $data['user_id'] = $user->id;
        $data['statut'] = 'en attente';

        // Génération d'une référence unique
        $date = now()->format('i_s');
        $random = strtoupper(bin2hex(random_bytes(3)));
        $reference = 'TK-' . $date . '-' . $random;
        $data['reference'] = $reference;

        $data['societe'] = $user->societe ?? 'Non spécifiée';

        // Gestion de la pièce jointe
        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('demandes', $fileName, 'public'); // stocké dans storage/app/public/demandes
            $data['fichier'] = $path; // on garde le chemin
        }

        $demande = Demandes::create($data);

        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $pj) {
                if (!$pj) { continue; }
                $name = time() . '_' . $pj->getClientOriginalName();
                $stored = $pj->storeAs('pieces_jointes', $name, 'public');
                PieceJointe::create([
                    'demande_id'  => $demande->id,
                    'nom_fichier' => $pj->getClientOriginalName(),
                    'chemin'      => $stored,
                    'type_mime'   => $pj->getMimeType(),
                    'taille'      => $pj->getSize(),
                    'extension'   => $pj->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('dashboardEmployer')
                        ->with('success', 'Demande créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Demandes $demande)
    {
        $demande->load('piecesJointes');
        return view('demandes.show', compact('demande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demandes $demande)
    {
        return view('demandes.edit', compact('demande'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDemandeRequest $request, $id)
    {
        $demande = Demandes::findOrFail($id);
        $data = $request->validated();

        // Formatage de la date_limite si elle existe
        if (!empty($data['date_limite'])) {
            $data['date_limite'] = \Carbon\Carbon::parse($data['date_limite'])->format('Y-m-d');
        }

        // Gestion de la mise à jour du fichier
        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier si existe
            if ($demande->fichier && Storage::disk('public')->exists($demande->fichier)) {
                Storage::disk('public')->delete($demande->fichier);
            }

            $file = $request->file('fichier');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('demandes', $fileName, 'public');
            $data['fichier'] = $path;
        }

        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $pj) {
                if (!$pj) { continue; }
                $name = time() . '_' . $pj->getClientOriginalName();
                $stored = $pj->storeAs('pieces_jointes', $name, 'public');
                PieceJointe::create([
                    'demande_id'  => $demande->id,
                    'nom_fichier' => $pj->getClientOriginalName(),
                    'chemin'      => $stored,
                    'type_mime'   => $pj->getMimeType(),
                    'taille'      => $pj->getSize(),
                    'extension'   => $pj->getClientOriginalExtension(),
                ]);
            }
        }

        $demande->update($data);

        // ⚡ Diffusion en temps réel
        event(new DemandeUpdated($demande));

        return redirect()->back()->with('success', 'Demande mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demandes $demande)
    {
        // Supprimer aussi le fichier lié si existe
        if ($demande->fichier && Storage::disk('public')->exists($demande->fichier)) {
            Storage::disk('public')->delete($demande->fichier);
        }

        $demande->delete();
        return redirect()->back()->with('success', 'Demande supprimée avec succès !');
    }

    /**
     * Mettre à jour uniquement le statut (AJAX)
     */
    public function updateStatus(Request $request, Demandes $demande)
    {
        $request->validate([
            'statut' => 'required|string|in:en attente,en cours,à risque,clôturé'
        ]);

        $demande->update(['statut' => $request->statut]);

        // ⚡ Diffusion en temps réel
        event(new DemandeUpdated($demande));

        return response()->json(['success' => true, 'message' => 'Statut mis à jour']);
    }

    public function assignDemande(Request $request)
    {
        try {
            $request->validate([
                'demande_id' => 'required|exists:demandes,id',
                'societe' => 'required|string|max:255',
                'statut' => 'required|string|in:en attente,en cours,à risque,clôturé'
            ]);

            $demande = Demandes::findOrFail($request->demande_id);

            // Trouver l'entreprise par son nom
            $entreprise = \App\Models\Entreprise::where('nom', $request->societe)->first();

            $demande->update([
                'societe_assignee' => $request->societe,
                'entreprise_id' => $entreprise ? $entreprise->id : null,
                'statut' => $request->statut
            ]);

            // Notify support users of that entreprise (type == 2)
            if ($entreprise) {
                $supportUsers = \App\Models\User::where('entreprise_id', $entreprise->id)
                    ->where('type', 2)
                    ->get();

                foreach ($supportUsers as $su) {
                    try {
                        \App\Models\Notifications::create([
                            'id_demande' => $demande->id,
                            'id_user' => $su->id,
                            'message' => "Nouvelle demande assignée: #{$demande->reference} - {$demande->titre}",
                            'type_notification' => 'assignment',
                            'read' => 0
                        ]);
                    } catch (\Exception $e) {
                        // Ignorer les erreurs de notification
                        \Log::warning("Erreur lors de l'envoi de la notification: " . $e->getMessage());
                    }
                }
            }

            // ⚡ Diffusion en temps réel si besoin
            event(new \App\Events\DemandeUpdated($demande));

            return response()->json([
                'success' => true,
                'message' => 'Demande mise à jour avec succès',
                'data' => $demande
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'affectation de la demande: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Une erreur est survenue lors de l'affectation: " . $e->getMessage()
            ], 500);
        }
    }
}
