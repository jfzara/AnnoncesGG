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
        Schema::table('annonces', function (Blueprint $table) {
            // Ajoute une colonne 'Titre' de type string (VARCHAR)
            // C'est un champ obligatoire, car le titre est essentiel pour une annonce
            $table->string('Titre')->after('Categorie'); // Vous pouvez ajuster 'after' si vous voulez la colonne ailleurs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            // Supprime la colonne 'Titre' si on annule la migration
            $table->dropColumn('Titre');
        });
    }
};
