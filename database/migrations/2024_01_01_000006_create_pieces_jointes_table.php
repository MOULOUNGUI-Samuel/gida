<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pieces_jointes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demandes_id'); // FK vers demande
            $table->string('fichier');
            $table->timestamps();

            $table->foreign('demandes_id')->references('id')->on('demandes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pieces_jointes');
    }
};
