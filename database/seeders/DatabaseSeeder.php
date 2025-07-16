<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,     // Crée tous les utilisateurs (admin + normaux)
            CategorieSeeder::class, // Crée les catégories
            AnnonceSeeder::class,  // Crée les annonces en s'appuyant sur les utilisateurs et catégories existants
        ]);
    }
}
