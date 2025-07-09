<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\CategorieSeeder;
use Database\Seeders\AnnonceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créez l'utilisateur 'jfzara' seulement s'il n'existe pas déjà
        User::firstOrCreate(
            ['email' => 'jfzara@gmail.com'], // Cherche par cette condition
            [ // Crée avec ces données si non trouvé
                'name' => 'jfzara',
                'password' => Hash::make('password'),
            ]
        );

        // Appelle le CategorieSeeder pour peupler les catégories
        $this->call(CategorieSeeder::class);

        // Appelle l'AnnonceSeeder pour peupler les annonces
        $this->call(AnnonceSeeder::class);
    }
}
