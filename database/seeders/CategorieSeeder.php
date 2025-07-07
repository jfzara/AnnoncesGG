<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie; // N'oubliez pas d'importer le modèle Categorie

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si des catégories existent déjà pour éviter les doublons
        if (Categorie::count() == 0) {
            Categorie::create(['Description' => 'Électronique']);
            Categorie::create(['Description' => 'Immobilier']);
            Categorie::create(['Description' => 'Véhicules']);
            Categorie::create(['Description' => 'Services']);
            Categorie::create(['Description' => 'Mode']);
            Categorie::create(['Description' => 'Loisirs']);
            // Ajoutez autant de catégories que nécessaire
        }
    }
}
