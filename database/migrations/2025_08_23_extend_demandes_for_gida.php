<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * EXÉCUTION DE LA MIGRATION
     */
    public function up(): void
    {
        Schema::table('demandes', function (Blueprint $table) {

            // CHAMPS AFFECTATION ET WORKFLOW
            $table->string('societe_assignee')->nullable();

            $table->unsignedBigInteger('assignee_user_id')
                ->nullable()
                ->after('societe_assignee')
                ->comment('Utilisateur spécifique assigné dans la société support');

            // WORKFLOW GIDA
            $table->enum('workflow_status', [
                'nouvelle',
                'analysee',
                'assignee',
                'en_traitement',
                'resolue',
                'validee',
                'cloturee'
            ])
            ->default('nouvelle')
            ->after('statut')
            ->comment('Statut détaillé du workflow GIDA');

            // QUALITÉ ET VALIDATION
            $table->integer('score_qualite')
                ->nullable()
                ->after('workflow_status')
                ->comment('Score qualité de 1 à 5');

            $table->timestamp('date_escalade')
                ->nullable()
                ->after('date_limite')
                ->comment('Date limite avant escalade automatique');

            $table->unsignedBigInteger('validateur_id')
                ->nullable()
                ->after('score_qualite')
                ->comment('Utilisateur validateur');

            $table->text('commentaire_validation')
                ->nullable()
                ->after('validateur_id');

            // AUTOMATISATION
            $table->json('mots_cles_detectes')
                ->nullable()
                ->after('commentaire_validation');

            $table->boolean('assignment_automatique')
                ->default(false)
                ->after('mots_cles_detectes');

            // PERFORMANCE
            $table->integer('temps_traitement_minutes')
                ->nullable()
                ->after('assignment_automatique');

            // FOREIGN KEYS
            $table->foreign('assignee_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validateur_id')->references('id')->on('users')->onDelete('set null');

            // INDEX
            $table->index(['workflow_status', 'societe_assignee'], 'idx_workflow_societe');
            $table->index(['date_escalade'], 'idx_date_escalade');
            $table->index(['assignment_automatique'], 'idx_assignment_auto');
        });
    }
};
