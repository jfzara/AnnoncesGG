<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Ajouté pour pouvoir hacher le mot de passe
use Database\Seeders\CategorieSeeder; // N'oubliez pas d'importer le CategorieSeeder si vous utilisez des chemins complets

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créez votre utilisateur spécifique 'jfzara'
        User::create([
            'name' => 'jfzara',
            'email' => 'jfzara@gmail.com',
            'password' => Hash::make('password'), // Utilise Hash::make pour hacher "password"
        ]);

        // Appelle le CategorieSeeder pour peupler les catégories
        $this->call(CategorieSeeder::class); // <-- AJOUTEZ OU DÉCOMMENTEZ CETTE LIGNE
    }
}
