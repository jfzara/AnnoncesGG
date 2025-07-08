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
    Schema::create('annonces', function (Blueprint $table) {
        $table->id('NoAnnonce'); // Clé primaire avec nom spécifique
        $table->unsignedBigInteger('NoUtilisateur'); // Référence à NoUtilisateur
        $table->timestamp('Parution'); // Date et heure de la parution de l’annonce
        $table->unsignedTinyInteger('Categorie'); // Catégorie de l'annonce

        // --- Modifications ici ---
        $table->string('DescriptionAbregee', 100); // Passé de 50 à 100 ou plus
        $table->text('DescriptionComplete');    // Passé de string(250) à text
        $table->string('Photo', 255);           // Passé de 50 à 255
        // --- Fin des modifications ---

        $table->decimal('Prix', 10, 2); // Prix de l'annonce
        $table->timestamp('MiseAJour')->nullable(); // Date et heure de mise à jour
        $table->unsignedTinyInteger('Etat'); // État de l'annonce

        // Ajoutez une contrainte de clé étrangère pour NoUtilisateur
        $table->foreign('NoUtilisateur')->references('NoUtilisateur')->on('utilisateurs')
            ->onDelete('cascade'); // Supprime les annonces si l'utilisateur est supprimé

        // Ajoutez une contrainte de clé étrangère pour Categorie
        $table->foreign('Categorie')->references('NoCategorie')->on('categories')
            ->onDelete('cascade'); // Supprime les annonces si la catégorie est supprimée

        $table->timestamps(); // Timestamps pour les créations et mises à jour
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
