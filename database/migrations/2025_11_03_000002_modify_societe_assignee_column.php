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
            // Vérifie si la colonne existe et la modifie proprement
            if (Schema::hasColumn('demandes', 'societe_assignee')) {
                $table->string('societe_assignee', 255)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // Retour à une version stricte (ou ce que tu veux)
            if (Schema::hasColumn('demandes', 'societe_assignee')) {
                $table->string('societe_assignee', 255)->nullable(false)->change();
            }
        });
    }
};
