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
            // Modifie la colonne 'Photo' existante pour la rendre nullable
            $table->string('Photo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            // Pour annuler : si vous voulez la rendre non-nullable à nouveau,
            // vous devrez vous assurer qu'aucune ligne ne contient de null
            // avant de la changer. Pour ce seeder, nous pouvons simplement
            // la remettre comme elle était si le besoin s'en fait sentir.
            // Note: 'change()' nécessite le package 'doctrine/dbal'
            $table->string('Photo')->nullable(false)->change(); // Remet la colonne non-nullable
        });
    }
};
