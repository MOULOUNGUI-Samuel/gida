<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id'); // FK vers ticket
            $table->integer('note');
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('ticket')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
