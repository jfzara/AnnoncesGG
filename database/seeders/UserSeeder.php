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
        User::create([
            'name' => 'jfzara',
            'email' => 'jfzara@gmail.com',
            'password' => Hash::make('password'), // Laravel va hacher 'password' pour vous
        ]);

        // Vous pouvez ajouter d'autres utilisateurs ici si vous le souhaitez
        // User::create([
        //     'name' => 'Autre Utilisateur',
        //     'email' => 'autre@example.com',
        //     'password' => Hash::make('secret'),
        // ]);
    }
}
