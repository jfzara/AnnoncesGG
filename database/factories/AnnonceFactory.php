<?php

namespace Database\Factories;

use App\Models\Annonce;
use App\Models\Categorie; // N'oubliez pas d'importer le modèle Categorie !
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnonceFactory extends Factory
{
    protected $model = Annonce::class;

    public function definition(): array
    {
        // Récupère une catégorie aléatoire existante
        $categorie = Categorie::inRandomOrder()->first();

        // Gère le cas où aucune catégorie n'est trouvée
        // Si CategorieSeeder a échoué ou n'a pas été exécuté, cela pourrait poser problème.
        $categorieId = $categorie ? $categorie->NoCategorie : null;

        // Optionnel: Si la colonne Categorie n'est pas nullable et $categorieId est null,
        // cela causera une erreur. Vous pouvez ajouter une vérification plus stricte ici.
        if (!$categorieId && Schema::hasColumn('annonces', 'Categorie') && !(new Annonce())->getConnection()->getSchemaBuilder()->getColumn('annonces', 'Categorie')['nullable']) {
            \Log::error("AnnonceFactory: La colonne 'Categorie' est requise mais aucune catégorie valide n'a été trouvée.");
            // Si vous voulez que le seeder échoue bruyamment, vous pouvez lancer une exception ici
            // throw new \Exception("Erreur de seeding: Catégorie introuvable pour AnnonceFactory.");
        }

        return [
            'NoUtilisateur' => User::factory(), // Sera surchargé par le AnnonceSeeder avec l'ID réel
            'Parution' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'Categorie' => $categorieId,
            'Titre' => $this->faker->sentence(rand(3, 7)),
            'DescriptionAbregee' => $this->faker->paragraph(rand(1, 3)),
            'DescriptionComplete' => $this->faker->paragraphs(rand(3, 7), true),
            'DateFin' => $this->faker->boolean(70) ? null : $this->faker->dateTimeBetween('now', '+3 months'),
            'Photo' => null, // Sera surchargé par le AnnonceSeeder
            'Prix' => $this->faker->randomFloat(2, 10, 1000),
            'MiseAJour' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'Etat' => $this->faker->boolean(90) ? 1 : 0,
        ];
    }
}
