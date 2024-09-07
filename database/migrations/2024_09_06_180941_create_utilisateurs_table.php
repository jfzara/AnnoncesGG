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
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id('NoUtilisateur'); // Clé primaire avec nom spécifique
            $table->string('Courriel', 50)->unique(); // Adresse courriel
            $table->string('MotDePasse', 15); // Mot de passe
            $table->timestamps(); // Création et mise à jour des timestamps
            $table->integer('NbConnexions')->default(0); // Nombre de connexions
            $table->integer('Statut'); // Statut
            $table->integer('NoEmpl')->nullable(); // Numéro d'employé
            $table->string('Nom', 25); // Nom
            $table->string('Prenom', 20); // Prénom
            $table->string('NoTelMaison', 15)->nullable(); // Téléphone maison
            $table->string('NoTelTravail', 21)->nullable(); // Téléphone travail
            $table->string('NoTelCellulaire', 15)->nullable(); // Téléphone cellulaire
            $table->text('AutresInfos')->nullable(); // Autres informations
            // Vous pouvez ajouter des contraintes supplémentaires ici, comme des validations pour les formats des numéros de téléphone
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
