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

            $table->string('Titre', 191); // Ajouté Titre ici, car il manquait dans votre extrait
            $table->string('DescriptionAbregee', 1000);
            $table->text('DescriptionComplete');
            $table->string('Photo', 255)->nullable(); // <-- Photo est nullable dans votre structure, assurez-vous de l'indiquer ici

            $table->decimal('Prix', 10, 2)->nullable(); // <-- MODIFICATION CLÉ : Rendre Prix nullable
            $table->timestamp('MiseAJour')->nullable(); // Date et heure de mise à jour
            $table->unsignedTinyInteger('Etat')->default(1); // <-- MODIFICATION : Ajouter une valeur par défaut (ex: 1 pour actif)

            // Ajoutez une contrainte de clé étrangère pour NoUtilisateur
            $table->foreign('NoUtilisateur')->references('id')->on('users') // <-- Assurez-vous que c'est 'id' si votre table users utilise 'id' comme clé primaire
                ->onDelete('cascade');

            // Ajoutez une contrainte de clé étrangère pour Categorie
            $table->foreign('Categorie')->references('NoCategorie')->on('categories')
                ->onDelete('cascade');

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
