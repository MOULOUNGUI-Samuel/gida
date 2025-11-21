<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demandes;
use App\Models\User;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifications;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        // Récupérer toutes les demandes pour l'administration
        $demandes = Demandes::with('user')->orderBy('created_at', 'desc')->get();
        
        // Récupérer les sociétés uniques depuis la table users et entreprises
        $societesUsers = User::whereNotNull('entreprise_id')
            ->with('entreprise')
            ->get()
            ->pluck('entreprise.nom', 'entreprise.id')
            ->unique()
            ->filter();
            
        $societesEntreprises = \App\Models\Entreprise::pluck('nom', 'id')->unique();
        
        // Fusionner les deux collections et supprimer les doublons
        $societes = $societesUsers->merge($societesEntreprises)->unique();
        
        // Statistiques des demandes
        $stats = [
            'total' => $demandes->count(),
            'en_cours' => $demandes->where('statut', 'en cours')->count(),
            'a_risque' => $demandes->where('statut', 'à risque')->count(),
            'cloturees' => $demandes->where('statut', 'clôturé')->count(),
        ];
        
        return view('dashboard', compact('demandes', 'stats', 'societes'));
    }

    public function dashboardEmployer()
    {
        // Récupérer les demandes de l'utilisateur connecté qui ne sont pas clôturées
        $user = Auth::user();
        $demandes = Demandes::where('user_id', $user->id)
                           ->where('statut', '!=', 'clôturé')
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return view('dashboardEmployer', compact('demandes'));
    }

    public function nouvelledemande()
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
        
        // Récupérer l'utilisateur connecté avec sa relation entreprise
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a une entreprise
        if (!$user->entreprise) {
            // Si l'utilisateur n'a pas d'entreprise, on passe une variable pour indiquer qu'il faut afficher un message d'erreur
            return view('components.employer.Nouvelledemande', [
                'user' => $user,
                'no_entreprise' => true
            ]);
        }
        
        // Charger la relation entreprise
        $user->load('entreprise');
        
        // Passer l'utilisateur à la vue
        return view('components.employer.Nouvelledemande', compact('user'));
    }

    public function historique()
    {
        $user = Auth::user();
        $demandes = Demandes::where('user_id', $user->id)
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return view('components.employer.Historique', compact('demandes'));
    }

    public function messagerie()
    {
        return view('components.employer.Mesagerie');
    }

    public function evaluation()
    {
        $user = Auth::user();
        
        // Récupérer la dernière demande de l'utilisateur
        $derniereDemande = \App\Models\Demandes::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Si aucune demande n'est trouvée, rediriger avec un message d'erreur
        if (!$derniereDemande) {
            return redirect()->route('dashboardEmployer')
                ->with('error', 'Aucune demande trouvée pour évaluation.');
        }

        return view('components.employer.Evaluation', [
            'demandeId' => $derniereDemande->id
        ]);
    }

    public function notification()
    {
        $user = Auth::user();
        $notifications = DB::table('notifications')
            ->leftJoin('users', 'notifications.id_user', '=', 'users.id')
            ->leftJoin('demandes', 'notifications.id_demande', '=', 'demandes.id')
            ->where('users.fonction', 'Administrateur')
            ->where('demandes.user_id', $user->id)
            ->orderBy('notifications.created_at', 'desc')
            ->select(
                'notifications.id as notification_id',
                'notifications.type_notification',
                'notifications.message',
                'notifications.read',
                'notifications.created_at',
                'demandes.id as id_demande',
                'users.id as user_id',
                'users.name as user_name'
                )
            ->get();
            
        return view('components.employer.Notification',  compact('notifications'));
    }

    public function notificationAdmin()
    {
        $notifications = DB::table('notifications')
            ->join('users', 'notifications.id_user', '=', 'users.id')
            ->where('users.fonction', 'Employe')
            ->orderBy('notifications.created_at', 'desc')
            ->select(
                'notifications.id as notification_id',
                'notifications.id_demande',
                'notifications.id_user',
                'notifications.message',
                'notifications.type_notification',
                'notifications.read',
                'notifications.created_at',
                'users.id as user_id',
                'users.name',
                'users.email'
                )
            ->get();
        return view('admin.notifications',  compact('notifications'));
    }

    public function profil()
    {
        $user = Auth::user();
        $demandes = Demandes::where('user_id', $user->id)->get();
        return view('components.employer.Profile', compact('user', 'demandes'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'societe' => 'required|string|max:255|unique:users,societe,' . $user->id,
            'code_entreprise' => 'required|string|max:255',
        ]);

        $user->update($request->only(['nom', 'username', 'email', 'societe', 'code_entreprise']));

        return redirect()->route('profil')->with('success', 'Profil mis à jour avec succès !');
    }

    // Méthodes pour l'administration
    public function qualification()
    {
        $demandes = Demandes::with('user')->where('statut', 'en attente')->orderBy('created_at', 'desc')->get();
        return view('admin.qualification', compact('demandes'));
    }

    public function supervision()
    {
        $demandes = Demandes::with('user')->whereIn('statut', ['en cours', 'à risque'])->orderBy('created_at', 'desc')->get();
        return view('admin.supervision', compact('demandes'));
    }

    public function reporting()
    {
        $demandes = Demandes::with('user')->orderBy('created_at', 'desc')->get();
        
        // Statistiques pour les graphiques
        $stats = [
            'total' => $demandes->count(),
            'en_cours' => $demandes->where('statut', 'en cours')->count(),
            'a_risque' => $demandes->where('statut', 'à risque')->count(),
            'cloturees' => $demandes->where('statut', 'clôturé')->count(),
            'par_categorie' => $demandes->groupBy('categorie')->map->count(),
            'par_societe' => $demandes->groupBy('societe')->map->count(),
        ];
        
        return view('admin.reporting', compact('demandes', 'stats'));
    }

    public function users()
    {
        $users = User::with('entreprise')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.users.index', compact('users'));
    }



    /**
     * MÉTHODES GIDA POUR LA QUALIFICATION ET L'AFFECTATION
     */
    
    // Sauvegarder la qualification d'une demande
    public function saveQualification(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'demande_id' => 'required|exists:demandes,id',
                'priorite' => 'required|in:normale,urgente,critique',
                'societe' => 'nullable|string|max:255',
                'commentaire_qualification' => 'nullable|string|max:1000'
            ]);

            // Récupérer la demande
            $demande = Demandes::findOrFail($validated['demande_id']);
            
            // Mettre à jour les champs de qualification
            $demande->update([
                'priorite' => $validated['priorite'],
                'societe_assignee' => $validated['societe'],
                'infos_supplementaires' => $validated['commentaire_qualification'],
                'workflow_status' => $validated['societe'] ? 'assignee' : 'analysee'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Qualification enregistrée avec succès',
                'data' => [
                    'demande_id' => $demande->id,
                    'workflow_status' => $demande->workflow_status,
                    'priorite' => $demande->priorite,
                    'societe' => $demande->societe_assignee
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ], 500);
        }
    }

    // Affecter une demande à une société
    public function assignDemande(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'demande_id' => 'required|exists:demandes,id',
                'societe' => 'required|string|max:255',
                'statut' => 'required|string|in:en attente,en cours,à risque,clôturé'
            ]);

            // Récupérer la demande
            $demande = Demandes::findOrFail($validated['demande_id']);
            
            // Mettre à jour la société assignée et le statut
            $updateData = [
                'societe_assignee' => $validated['societe'],
                'workflow_status' => 'assignee',
                'statut' => $validated['statut']
            ];

            // Si la demande est marquée comme "en cours", enregistrer la date de début
            if ($validated['statut'] === 'en cours' && !$demande->date_debut) {
                $updateData['date_debut'] = now();
            }
            
            // Si la demande est marquée comme "clôturée", enregistrer la date de clôture
            if ($validated['statut'] === 'clôturé') {
                $updateData['date_fermeture'] = now();
            }

            $demande->update($updateData);

            // Déclencher l'événement de mise à jour
            event(new \App\Events\DemandeUpdated($demande));

            return response()->json([
                'success' => true,
                'message' => 'Demande mise à jour avec succès',
                'demande' => $demande->fresh()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    // Récupérer les détails complets d'une demande pour le modal
    public function getDemandeDetails($id)
    {
        try {
            $demande = Demandes::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $demande->id,
                    'titre' => $demande->titre,
                    'description' => $demande->description,
                    'categorie' => $demande->categorie,
                    'priorite' => $demande->priorite,
                    'statut' => $demande->statut,
                    'workflow_status' => $demande->workflow_status,
                    'societe' => $demande->societe,
                    'societe_assignee' => $demande->societe_assignee,
                    'commentaire' => $demande->commentaire,
                    'created_at' => $demande->created_at,
                    'date_limite' => $demande->date_limite ? $demande->date_limite->format('d/m/Y') : null,
                    'nom' => $demande->nom,
                    'fonction' => $demande->fonction,
                    'mail' => $demande->mail,
                    'is_overdue' => $demande->date_limite ? $demande->date_limite->isPast() : false
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Demande non trouvée'
            ], 404);
        }
    }

    /**
     * MÉTHODES UTILITAIRES GIDA
     */
    
    // Calculer la date d'escalade selon la priorité
    private function calculateEscalationDate($priorite)
    {
        $hours = match($priorite) {
            'critique' => 4,   // 4 heures
            'urgente' => 24,   // 1 jour
            'normale' => 72,   // 3 jours
            default => 72
        };
        
        return now()->addHours($hours);
    }

    // Créer un log de workflow (à implémenter avec le modèle WorkflowLog)
    private function createWorkflowLog($demandeId, $action, $description, $metadata = [])
    {
        // Pour l'instant, on peut logger dans les logs Laravel
        \Log::info("Workflow GIDA - Demande #{$demandeId}", [
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);
        
        // TODO: Implémenter le modèle WorkflowLog pour un suivi plus détaillé
    }

    // Afficher la vue de contrôle qualité pour un ticket spécifique
    public function qualityControl($id)
    {
        try {
            $demande = Demandes::with(['user', 'assignedUser', 'validator'])
                              ->findOrFail($id);

            return view('admin.supervision', compact('demande'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Ticket non trouvé');
        }
    }

    // Sauvegarder le contrôle qualité
    public function saveQualityControl(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'demande_id' => 'required|exists:demandes,id',
                'quality_rating' => 'required|in:excellent,bon,moyen,insuffisant',
                'validation_comments' => 'required|string|max:2000',
                'customer_satisfaction' => 'nullable|in:tres_satisfait,satisfait,peu_satisfait,insatisfait',
                'action' => 'required|in:approved,modifications_requested,rejected'
            ]);

            // Récupérer la demande
            $demande = Demandes::findOrFail($validated['demande_id']);
            
            // Déterminer le nouveau statut selon l'action
            $nouveauStatut = match($validated['action']) {
                'approved' => 'clôturé',
                'modifications_requested' => 'en cours',
                'rejected' => 'à risque',
                default => $demande->statut
            };

            // Mettre à jour la demande avec les données de contrôle qualité
            $demande->update([
                'statut' => $nouveauStatut,
                'infos_supplementaires' => $validated['validation_comments']
            ]);

            $message = match($validated['action']) {
                'approved' => 'Ticket approuvé et clôturé avec succès',
                'modifications_requested' => 'Modifications demandées à la société assignée',
                'rejected' => 'Clôture du ticket rejetée',
                default => 'Contrôle qualité enregistré'
            };

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'demande_id' => $demande->id,
                    'statut' => $demande->statut,
                    'action' => $validated['action']
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du contrôle qualité: ' . $e->getMessage()
            ], 500);
        }
    }

}
