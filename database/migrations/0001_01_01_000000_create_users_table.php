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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->string('username')->unique(); // 🔑 Nouveau champ username
            $table->tinyInteger('type')->default(1); // 0 = admin, 1 = utilisateur
            $table->string('nom');
            $table->string('code_entreprise');
            $table->string('fonction')->nullable();
            $table->string('societe')->nullable();
            $table->string('matricule')->unique();// Identifiant optionnel
            $table->string('password'); // Mot de passe hashé
            $table->string('email')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('entreprise_id')->nullable(); // FK vers entreprise
            $table->string('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
