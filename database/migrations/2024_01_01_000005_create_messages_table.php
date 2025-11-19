<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id'); // FK vers ticket
            $table->unsignedBigInteger('sender_id'); // FK vers users
            $table->unsignedBigInteger('receiver_id'); // FK vers users
            $table->text('contenu');
            $table->dateTime('date');
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('ticket')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
