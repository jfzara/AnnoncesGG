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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id(); // Colonne d'ID auto-incrémentée

            // Clé étrangère vers la table 'users'
            // Assurez-vous que votre table users utilise 'id' comme clé primaire
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Clé étrangère vers la table 'annonces'
            // 'annonces' est le nom de la table, et 'NoAnnonce' est le nom de la clé primaire de cette table
            $table->foreignId('annonce_id')->constrained('annonces', 'NoAnnonce')->onDelete('cascade');

            $table->timestamps(); // Colonnes created_at et updated_at

            // Contrainte d'unicité pour s'assurer qu'un utilisateur ne peut ajouter
            // la même annonce en favori qu'une seule fois
            $table->unique(['user_id', 'annonce_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
