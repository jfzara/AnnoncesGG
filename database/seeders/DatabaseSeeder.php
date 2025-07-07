<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Ajouté pour pouvoir hacher le mot de passe

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Supprimez ou commentez la ligne qui utilise factory()->create() pour l'utilisateur de test générique
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Créez votre utilisateur spécifique 'jfzara'
        User::create([
            'name' => 'jfzara',
            'email' => 'jfzara@gmail.com',
            'password' => Hash::make('password'), // Utilise Hash::make pour hacher "password"
        ]);
    }
}
