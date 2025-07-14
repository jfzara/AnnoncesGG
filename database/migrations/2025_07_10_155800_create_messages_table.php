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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annonce_id')->constrained('annonces')->onDelete('cascade'); // L'annonce à laquelle le message est lié
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');    // L'expéditeur du message
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');  // Le destinataire du message
            $table->text('content');                                                       // Le contenu du message
            $table->boolean('read_at_sender')->default(false);                             // Si l'expéditeur a lu la conversation (utile pour le UI)
            $table->boolean('read_at_receiver')->default(false);                           // Si le destinataire a lu le message
            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
