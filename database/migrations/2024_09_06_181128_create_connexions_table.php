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
        Schema::create('connexions', function (Blueprint $table) {
            $table->id('NoConnexion'); // Clé primaire avec nom spécifique
            $table->unsignedBigInteger('NoUtilisateur'); // Référence à NoUtilisateur
            $table->timestamp('Connexion'); // Date et heure de connexion
            $table->timestamp('Deconnexion')->nullable(); // Date et heure de déconnexion, si applicable
            
            // Ajoutez une contrainte de clé étrangère pour NoUtilisateur
            $table->foreign('NoUtilisateur')->references('NoUtilisateur')->on('utilisateurs')
                ->onDelete('cascade'); // Supprime les connexions si l'utilisateur est supprimé

            $table->timestamps(); // Timestamps pour les créations et mises à jour
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connexions');
    }
};
