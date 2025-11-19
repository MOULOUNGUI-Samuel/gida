<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * MODÈLE DEMANDES GIDA
 * Modèle principal pour la gestion des demandes d'assistance dans le système GIDA
 * Étendu avec les fonctionnalités de workflow, affectation et validation
 */
class Demandes extends Model


{



    
    use HasFactory;

    // Nom de la table associée
    protected $table = 'demandes';

    // Champs autorisés à l'insertion en masse (étendus pour GIDA)
    protected $fillable = [
        // Champs originaux
        'titre',
        'categorie',
        'priorite',
        'date_limite',
        'nom',
        'reference',
        'fonction',
        'societe',
        'description',
        'mail',
        'infos_supplementaires',
        'fichier',
        'statut',
        'user_id',
        
        // Nouveaux champs GIDA
        'societe_assignee',
    'assignee_user_id',
    'entreprise_id',
        'workflow_status',
        'score_qualite',
        'date_escalade',
        'validateur_id',
        'commentaire_validation',
        'mots_cles_detectes',
        'assignment_automatique',
        'temps_traitement_minutes'
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs.
     *
     * @var array
     */
    protected $dates = [
        'date_limite',
        'date_escalade',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Champs à caster (étendus pour GIDA)
    protected $casts = [
        'date_limite' => 'date:Y-m-d',
        'date_escalade' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'mots_cles_detectes' => 'array',
        'assignment_automatique' => 'boolean'
    ];
    
    /**
     * Préparer une date pour l'affichage.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    /**
     * RELATIONS LARAVEL
     */
    
    // Relation avec l'utilisateur demandeur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec l'utilisateur assigné dans la société support
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assignee_user_id');
    }

    // Relation with the entreprise (support company)
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    // Pièces jointes associées
    public function piecesJointes()
    {
        return $this->hasMany(PieceJointe::class, 'demande_id');
    }

    // Relation avec le validateur BFEV
    public function validator()
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    // Relation avec les logs de workflow
    public function workflowLogs()
    {
        return $this->hasMany(WorkflowLog::class, 'demande_id');
    }

    // Relation avec les communications
    public function communications()
    {
        return $this->hasMany(Communication::class, 'demande_id');
    }

    /**
     * SCOPES GIDA POUR REQUÊTES OPTIMISÉES
     */
    
    // Demandes nécessitant une affectation
    public function scopeNeedingAssignment($query)
    {
        return $query->where('workflow_status', 'analysee')
                    ->whereNull('societe_assignee');
    }
    
    // Demandes en retard (dépassement date escalade)
    public function scopeOverdue($query)
    {
        return $query->where('date_escalade', '<', now())
                    ->whereNotIn('workflow_status', ['validee', 'cloturee']);
    }
    
    // Demandes par société support
    public function scopeByCompany($query, $company)
    {
        return $query->where('societe_assignee', $company);
    }
    
    // Demandes par statut workflow
    public function scopeByWorkflowStatus($query, $status)
    {
        return $query->where('workflow_status', $status);
    }
    
    // Demandes critiques (priorité haute + en retard)
    public function scopeCritical($query)
    {
        return $query->where('priorite', 'critique')
                    ->where(function($q) {
                        $q->where('date_escalade', '<', now())
                          ->orWhere('workflow_status', 'nouvelle');
                    });
    }

    /**
     * ACCESSEURS POUR FORMATAGE ET AFFICHAGE
     */
    
    // Accesseur pour formater la date de création
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    // Accesseur pour obtenir la classe CSS du statut original
    public function getStatusClassAttribute()
    {
        return match($this->statut) {
            'en attente' => 'status-pending',
            'en cours' => 'status-progress',
            'à risque' => 'status-risk',
            'clôturé' => 'status-closed',
            default => 'status-default'
        };
    }

    // Accesseur pour la classe CSS du workflow GIDA
    public function getWorkflowClassAttribute()
    {
        return match($this->workflow_status) {
            'nouvelle' => 'workflow-nouvelle',
            'analysee' => 'workflow-analysee',
            'assignee' => 'workflow-assignee',
            'en_traitement' => 'workflow-en-traitement',
            'resolue' => 'workflow-resolue',
            'validee' => 'workflow-validee',
            'cloturee' => 'workflow-cloturee',
            default => 'workflow-default'
        };
    }

    // Accesseur pour le libellé du workflow en français
    public function getWorkflowLabelAttribute()
    {
        return match($this->workflow_status) {
            'nouvelle' => 'Nouvelle',
            'analysee' => 'Analysée',
            'assignee' => 'Assignée',
            'en_traitement' => 'En traitement',
            'resolue' => 'Résolue',
            'validee' => 'Validée',
            'cloturee' => 'Clôturée',
            default => 'Indéfini'
        };
    }

    // Accesseur pour vérifier si la demande est en retard
    public function getIsOverdueAttribute()
    {
        return $this->date_escalade && 
               $this->date_escalade < now() && 
               !in_array($this->workflow_status, ['validee', 'cloturee']);
    }

    // Accesseur pour le temps écoulé depuis création
    public function getTimeElapsedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * MÉTHODES MÉTIER GIDA
     */
    
    // Calculer le temps de traitement en cours
    public function calculateProcessingTime()
    {
        if ($this->workflow_status === 'cloturee' && $this->temps_traitement_minutes) {
            return $this->temps_traitement_minutes;
        }
        
        return $this->created_at->diffInMinutes(now());
    }
    
    // Vérifier si la demande peut être assignée
    public function canBeAssigned()
    {
        return $this->workflow_status === 'analysee' && !$this->societe_assignee;
    }
    
    // Vérifier si la demande peut être validée
    public function canBeValidated()
    {
        return $this->workflow_status === 'resolue';
    }
    
    // Obtenir la couleur de priorité
    public function getPriorityColor()
    {
        return match($this->priorite) {
            'critique' => '#dc3545',
            'urgente' => '#ffc107', 
            'normale' => '#28a745',
            default => '#6c757d'
        };
    }

    /**
     * MÉTHODES D'AFFECTATION AUTOMATIQUE
     */
    
    // Analyser le contenu pour détecter les mots-clés
    public function detectKeywords()
    {
        $content = strtolower($this->titre . ' ' . $this->description);
        $keywords = [];
        
        // Mots-clés COMKETING (Marketing/Communication)
        $marketing_keywords = ['marketing', 'communication', 'publicité', 'brand', 'campagne', 'social media'];
        foreach ($marketing_keywords as $keyword) {
            if (str_contains($content, $keyword)) {
                $keywords[] = $keyword;
            }
        }
        
        // Mots-clés YOD INGÉNIERIE (Technique)
        $tech_keywords = ['technique', 'développement', 'infrastructure', 'serveur', 'application', 'bug'];
        foreach ($tech_keywords as $keyword) {
            if (str_contains($content, $keyword)) {
                $keywords[] = $keyword;
            }
        }
        
        // Mots-clés FCI (Finance)
        $finance_keywords = ['finance', 'investissement', 'comptabilité', 'budget', 'facture', 'paiement'];
        foreach ($finance_keywords as $keyword) {
            if (str_contains($content, $keyword)) {
                $keywords[] = $keyword;
            }
        }
        
        // Mots-clés ALPHON CONSULTING (Management)
        $management_keywords = ['management', 'organisation', 'rh', 'stratégie', 'formation', 'processus'];
        foreach ($management_keywords as $keyword) {
            if (str_contains($content, $keyword)) {
                $keywords[] = $keyword;
            }
        }
        
        return $keywords;
    }
    
    // Suggérer une société support basée sur les mots-clés
    public function suggestAssignment()
    {
        $keywords = $this->detectKeywords();
        $content = strtolower($this->titre . ' ' . $this->description);
        
        $scores = [
            'COMKETING' => 0,
            'YOD INGÉNIERIE' => 0,
            'FCI' => 0,
            'ALPHON CONSULTING' => 0
        ];
        
        // Calcul des scores par société
        foreach ($keywords as $keyword) {
            if (in_array($keyword, ['marketing', 'communication', 'publicité', 'brand', 'campagne', 'social media'])) {
                $scores['COMKETING']++;
            } elseif (in_array($keyword, ['technique', 'développement', 'infrastructure', 'serveur', 'application', 'bug'])) {
                $scores['YOD INGÉNIERIE']++;
            } elseif (in_array($keyword, ['finance', 'investissement', 'comptabilité', 'budget', 'facture', 'paiement'])) {
                $scores['FCI']++;
            } elseif (in_array($keyword, ['management', 'organisation', 'rh', 'stratégie', 'formation', 'processus'])) {
                $scores['ALPHON CONSULTING']++;
            }
        }
        
        // Retourner la société avec le score le plus élevé
        return array_key_first(array_filter($scores, fn($score) => $score === max($scores)));
    }


    
}
