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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_demande');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('message', 255);
            $table->string('type_notification')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();
            // relations
            $table->foreign('id_demande')->references('id')->on('demandes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
