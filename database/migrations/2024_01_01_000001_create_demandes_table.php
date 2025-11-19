<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandesTable extends Migration
{
    public function up()
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('categorie');
            $table->string('priorite');
            $table->date('date_limite')->nullable();
            $table->string('nom');
            $table->string('reference')->nullable();
            $table->string('fonction')->nullable();
            $table->string('societe')->nullable();
            $table->text('description');
            $table->string('mail'); // utilisé pour stocker l’email
            $table->text('infos_supplementaires')->nullable();
            $table->string('fichier')->nullable();
            $table->string('statut')->default('en attente');

            // Relation avec users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandes');
    }
}
