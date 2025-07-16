<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optionnel : Vider la table des utilisateurs pour un re-seeding propre.
        // Utilisez ceci si vous voulez un état de base de données toujours propre à chaque exécution.
        // Si vous utilisez `migrate:fresh`, le truncate n'est pas strictement nécessaire ici mais ne nuit pas.
        User::truncate();

        // 1. Crée l'utilisateur 'admin' avec un rôle 'admin'
        User::firstOrCreate( // Utilise firstOrCreate pour éviter les doublons si vous ne faites pas de truncate
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Liste des noms et emails pour les utilisateurs "normaux"
        $normalUsersData = [
            ['name' => 'Alice Martin', 'email' => 'alice.martin@gmail.com'],
            ['name' => 'Bob Dubois', 'email' => 'bob.dubois@gmail.com'],
            ['name' => 'Charlie Dupont', 'email' => 'charlie.dupont@gmail.com'],
            ['name' => 'Diana Leclerc', 'email' => 'diana.leclerc@gmail.com'],
            ['name' => 'Étienne Moreau', 'email' => 'etienne.moreau@gmail.com'],
            ['name' => 'Fanny Petit', 'email' => 'fanny.petit@gmail.com'],
            ['name' => 'Gilles Roy', 'email' => 'gilles.roy@gmail.com'],
            ['name' => 'Hélène Gagnon', 'email' => 'helene.gagnon@gmail.com'],
            ['name' => 'Ivan Tremblay', 'email' => 'ivan.tremblay@gmail.com'],
            // Vous pouvez ajouter d'autres utilisateurs ici si vous en voulez plus
        ];

        // 3. Crée chaque utilisateur "normal" à partir de la liste
        foreach ($normalUsersData as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'user',
                ]
            );
        }

        // Information de la console sur le nombre total d'utilisateurs créés
        $this->command->info(User::count() . " utilisateurs ont été créés (y compris l'Admin).");
    }
}
