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
            // Ajoute une colonne 'DateFin' de type date/heure
            // nullable() permet aux annonces existantes de ne pas avoir de date d'expiration
            $table->dateTime('DateFin')->nullable()->after('DescriptionComplete'); // Vous pouvez ajuster 'after' si vous voulez la colonne ailleurs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            // Supprime la colonne 'DateFin' si on annule la migration
            $table->dropColumn('DateFin');
        });
    }
};
