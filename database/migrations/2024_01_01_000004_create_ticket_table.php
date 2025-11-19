<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demandes_id'); // FK vers demandes
            $table->string('statut');
            $table->date('date_ouverture');
            $table->date('date_cloture')->nullable();
            $table->timestamps();

            $table->foreign('demandes_id')->references('id')->on('demandes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket');
    }
};
