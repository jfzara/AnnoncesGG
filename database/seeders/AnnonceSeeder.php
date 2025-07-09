<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Annonce;
use App\Models\User; // Pour lier les annonces à un utilisateur existant
use App\Models\Categorie; // Pour lier les annonces à une catégorie existante
use Illuminate\Support\Facades\DB; // Pour la suppression des anciennes données
use Illuminate\Support\Facades\Storage; // Pour gérer les fichiers d'images

class AnnonceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Optionnel : Nettoyer les anciennes annonces et photos existantes
        // Utile si vous relancez le seeder fréquemment
        DB::table('annonces')->truncate();
        Storage::disk('public')->deleteDirectory('annonces_photos'); // Supprime le dossier des images
        Storage::disk('public')->makeDirectory('annonces_photos'); // Recrée le dossier vide

        // Assurez-vous qu'il y a au moins un utilisateur et une catégorie dans la DB
        $user = User::first(); // Prend le premier utilisateur
        if (!$user) {
            // Crée un utilisateur par défaut si aucun n'existe
            $user = User::factory()->create([
                'name' => 'Utilisateur Test',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $categorie = Categorie::first(); // Prend la première catégorie
        if (!$categorie) {
            // Crée une catégorie par défaut si aucune n'existe
            $categorie = Categorie::factory()->create([
                'NoCategorie' => 1, // Assurez-vous que c'est un ID valide ou qu'il s'auto-incrémente
                'NomCategorie' => 'Autres',
            ]);
        }

        // --- Création de fausses images de démonstration ---
        // (Pour ne pas avoir à en uploader manuellement)
        // Télécharge une image de placeholder pour les tests
        $demoImage1 = 'annonces_photos/annonce_velo.jpg';
        $demoImage2 = 'annonces_photos/annonce_appart.jpg';
        $demoImage3 = 'annonces_photos/annonce_chaise.jpg';

        //file_put_contents(storage_path('app/public/'.$demoImage1), file_get_contents('https://via.placeholder.com/640x480/FF5733/FFFFFF?text=Velo+Demo'));
       // file_put_contents(storage_path('app/public/'.$demoImage2), file_get_contents('https://via.placeholder.com/640x480/33FF57/FFFFFF?text=Appartement+Demo'));
        //file_put_contents(storage_path('app/public/'.$demoImage3), file_get_contents('https://via.placeholder.com/640x480/3357FF/FFFFFF?text=Chaise+Demo'));


        // Création de 5 annonces de test
        Annonce::create([
            'NoUtilisateur' => $user->id,
            'Parution' => now()->subDays(5), // Publiée il y a 5 jours
            'Categorie' => $categorie->NoCategorie,
            'Titre' => 'Vélo de montagne quasi neuf',
            'DescriptionAbregee' => 'Excellent VTT, très peu utilisé. Idéal pour sentiers et ville.',
            'DescriptionComplete' => 'Vélo de montagne en aluminium, suspension avant, freins à disque. Taille M. Vendu car plus le temps d\'en faire. Quelques égratignures mineures. Prix négociable.',
            'Prix' => 750.00,
            'Photo' => null,
            'MiseAJour' => now()->subDays(2),
            'Etat' => 1, // Active
            'DateFin' => now()->addDays(10), // Expire dans 10 jours
        ]);

        Annonce::create([
            'NoUtilisateur' => $user->id,
            'Parution' => now()->subDays(10), // Publiée il y a 10 jours
            'Categorie' => $categorie->NoCategorie,
            'Titre' => 'Appartement à louer - Centre-ville',
            'DescriptionAbregee' => 'Grand 4 1/2 lumineux, proche de tous services.',
            'DescriptionComplete' => 'Appartement spacieux avec deux chambres, salon, cuisine équipée. Balcon. À 5 minutes à pied du métro. Idéal pour couple ou petite famille. Disponible le 1er septembre.',
            'Prix' => 1200.00,
            'Photo' => null,
            'MiseAJour' => now()->subDays(1),
            'Etat' => 1, // Active
            'DateFin' => null, // Pas de date d'expiration
        ]);

        Annonce::create([
            'NoUtilisateur' => $user->id,
            'Parution' => now()->subDays(20), // Publiée il y a 20 jours
            'Categorie' => $categorie->NoCategorie,
            'Titre' => 'Chaise de bureau ergonomique',
            'DescriptionAbregee' => 'Confortable et ajustable, parfait pour le télétravail.',
            'DescriptionComplete' => 'Chaise de bureau avec support lombaire, accoudoirs réglables, et hauteur ajustable. En excellent état. Marque connue. Vendu car double emploi.',
            'Prix' => 150.00,
            'Photo' => null,
            'MiseAJour' => now()->subDays(5),
            'Etat' => 1, // Active
            'DateFin' => now()->addDays(5), // Expire dans 5 jours
        ]);

        Annonce::create([
            'NoUtilisateur' => $user->id,
            'Parution' => now()->subDays(30), // Publiée il y a 30 jours
            'Categorie' => $categorie->NoCategorie,
            'Titre' => 'Vieux piano à vendre (urgent)',
            'DescriptionAbregee' => 'Piano droit à restaurer ou pour pièces. Doit partir vite !',
            'DescriptionComplete' => 'Ancien piano familial, quelques touches bloquées, besoin d\'un accordage complet. Idéal pour amateur de restauration ou collectionneur. À venir chercher sur place, lourd !',
            'Prix' => 50.00,
            'Photo' => null, // Réutilise une image
            'MiseAJour' => now()->subDays(10),
            'Etat' => 1, // Active
            'DateFin' => now()->subDays(2), // <<< EXPRIRÉE : Devrait être cachée par la nouvelle logique
        ]);

        Annonce::create([
            'NoUtilisateur' => $user->id,
            'Parution' => now()->subDays(7),
            'Categorie' => $categorie->NoCategorie,
            'Titre' => 'Lot de livres de cuisine',
            'DescriptionAbregee' => 'Plus de 20 livres, recettes variées du monde entier.',
            'DescriptionComplete' => 'Collection de livres de cuisine, certains neufs, d\'autres légèrement usagés. Recettes italiennes, asiatiques, végétariennes, pâtisserie, etc. Idéal pour cuisinier amateur.',
            'Prix' => 80.00,
            'Photo' => null, // Réutilise une image
            'MiseAJour' => now()->subDays(3),
            'Etat' => 1,
            'DateFin' => now()->addDays(20),
        ]);
    }
}
