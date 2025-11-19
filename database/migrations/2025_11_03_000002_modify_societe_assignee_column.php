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
            // Modifie la colonne societe_assignee pour accepter n'importe quelle chaîne
            DB::statement('ALTER TABLE demandes MODIFY societe_assignee VARCHAR(255);');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // Rétablit l'enum d'origine
            DB::statement("ALTER TABLE demandes MODIFY societe_assignee ENUM('COMKETING', 'YOD INGÉNIERIE', 'FCI', 'ALPHON CONSULTING') NULL;");
        });
    }
};