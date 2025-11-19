<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * MIGRATION GIDA - EXTENSION TABLE DEMANDES
 * Cette migration étend la table demandes existante avec les champs nécessaires
 * pour le système GIDA (Gestion Intégrée des Demandes d'Assistance)
 * 
 * Nouveaux champs ajoutés :
 * - societe_assignee : Société support assignée (COMKETING, YOD INGÉNIERIE, FCI, ALPHON CONSULTING)
 * - assignee_user_id : Utilisateur spécifique assigné dans la société support
 * - workflow_status : Statut détaillé du workflow GIDA
 * - score_qualite : Note de qualité de 1 à 5 après validation
 * - date_escalade : Date limite avant escalade automatique
 * - validateur_id : Utilisateur qui a validé la demande
 * - commentaire_validation : Commentaire du validateur
 * - mots_cles_detectes : Mots-clés détectés pour l'affectation automatique
 * - assignment_automatique : Indique si l'affectation a été automatique
 * - temps_traitement_minutes : Temps de traitement effectif en minutes
 */
return new class extends Migration
{
    /**
     * EXÉCUTION DE LA MIGRATION
     * Ajoute les nouveaux champs GIDA à la table demandes existante
     */
    public function up(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // CHAMPS AFFECTATION ET WORKFLOW
            $table->enum('societe_assignee', [
                'COMKETING',           // Marketing/Communication
                'YOD INGÉNIERIE',      // Technique/Ingénierie  
                'FCI',                 // Finance/Investissement
                'ALPHON CONSULTING'    // Management/Organisation
            ])->nullable()->after('societe')->comment('Société support assignée pour traiter la demande');
            
            $table->unsignedBigInteger('assignee_user_id')->nullable()->after('societe_assignee')
                  ->comment('Utilisateur spécifique assigné dans la société support');
            
            // WORKFLOW GIDA DÉTAILLÉ
            $table->enum('workflow_status', [
                'nouvelle',        // Demande créée, pas encore analysée
                'analysee',        // Analysée par BFEV, prête pour affectation
                'assignee',        // Assignée à une société support
                'en_traitement',   // En cours de traitement par la société
                'resolue',         // Résolue par la société, en attente de validation
                'validee',         // Validée par BFEV, satisfaisante
                'cloturee'         // Clôturée définitivement
            ])->default('nouvelle')->after('statut')->comment('Statut détaillé du workflow GIDA');
            
            // CHAMPS QUALITÉ ET VALIDATION
            $table->integer('score_qualite')->nullable()->after('workflow_status')
                  ->comment('Score qualité de 1 à 5 attribué après validation');
            
            $table->timestamp('date_escalade')->nullable()->after('date_limite')
                  ->comment('Date limite avant escalade automatique');
            
            $table->unsignedBigInteger('validateur_id')->nullable()->after('score_qualite')
                  ->comment('ID de l\'utilisateur qui a validé la demande');
            
            $table->text('commentaire_validation')->nullable()->after('validateur_id')
                  ->comment('Commentaire du validateur lors de la validation');
            
            // CHAMPS AUTOMATISATION
            $table->json('mots_cles_detectes')->nullable()->after('commentaire_validation')
                  ->comment('Mots-clés détectés pour l\'affectation automatique');
            
            $table->boolean('assignment_automatique')->default(false)->after('mots_cles_detectes')
                  ->comment('Indique si l\'affectation a été faite automatiquement');
            
            // MÉTRIQUES DE PERFORMANCE
            $table->integer('temps_traitement_minutes')->nullable()->after('assignment_automatique')
                  ->comment('Temps de traitement effectif en minutes');
            
            // CLÉS ÉTRANGÈRES
            $table->foreign('assignee_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validateur_id')->references('id')->on('users')->onDelete('set null');
            
            // INDEX POUR OPTIMISER LES PERFORMANCES
            $table->index(['workflow_status', 'societe_assignee'], 'idx_workflow_societe');
            $table->index(['date_escalade'], 'idx_date_escalade');
            $table->index(['assignment_automatique'], 'idx_assignment_auto');
        });
    }

    /**
     * ANNULATION DE LA MIGRATION
     * Supprime tous les champs GIDA ajoutés
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // Supprimer les index
            $table->dropIndex('idx_workflow_societe');
            $table->dropIndex('idx_date_escalade');
            $table->dropIndex('idx_assignment_auto');
            
            // Supprimer les clés étrangères
            $table->dropForeign(['assignee_user_id']);
            $table->dropForeign(['validateur_id']);
            
            // Supprimer les colonnes
            $table->dropColumn([
                'societe_assignee',
                'assignee_user_id', 
                'workflow_status',
                'score_qualite',
                'date_escalade',
                'validateur_id',
                'commentaire_validation',
                'mots_cles_detectes',
                'assignment_automatique',
                'temps_traitement_minutes'
            ]);
        });
    }
};
