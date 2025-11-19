<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // ⚠️ Ne pas dupliquer "societe_assignee"
            if (!Schema::hasColumn('demandes', 'workflow_status')) {
                $table->string('workflow_status')->default('en_attente')->after('societe');
            }

            if (!Schema::hasColumn('demandes', 'commentaire_assignation')) {
                $table->text('commentaire_assignation')->nullable()->after('workflow_status');
            }

            if (!Schema::hasColumn('demandes', 'date_assignation')) {
                $table->timestamp('date_assignation')->nullable()->after('commentaire_assignation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn([
                'workflow_status',
                'commentaire_assignation',
                'date_assignation'
            ]);
        });
    }
};
