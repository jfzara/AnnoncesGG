<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Pour hacher le mot de passe
use App\Models\User; // Pour utiliser votre modèle User

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création du premier utilisateur 'jfzara' s'il n'existe pas déjà
        User::firstOrCreate(
            ['email' => 'jfzara@gmail.com'], // Critère de recherche : l'email
            [
                'name' => 'jfzara',
                'password' => Hash::make('password'), // Hash le mot de passe 'password'
                'email_verified_at' => now(), // Simule une adresse email vérifiée
            ]
        );

        // Création des 5 utilisateurs jfzara1 à jfzara5
        for ($i = 1; $i <= 5; $i++) {
            User::firstOrCreate(
                ['email' => 'jfzara' . $i . '@gmail.com'], // Critère de recherche : l'email
                [
                    'name' => 'jfzara' . $i,
                    'password' => Hash::make('password'), // Hash le mot de passe 'password'
                    'email_verified_at' => now(), // Simule une adresse email vérifiée
                ]
            );
        }
    }
}
