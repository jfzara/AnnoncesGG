<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriesToSeed = [
            'Véhicules - Voitures',
            'Véhicules - Motos',
            'Véhicules - Camions & Utilitaires',
            'Véhicules - Véhicules Récréatifs (VR)',
            'Véhicules - Bateaux & Sports Nautiques',
            'Véhicules - Pièces & Accessoires',
            'Véhicules - Autres Véhicules',

            'Immobilier - Appartements à Louer',
            'Immobilier - Maisons à Louer',
            'Immobilier - Appartements à Vendre',
            'Immobilier - Maisons à Vendre',
            'Immobilier - Terrains & Lots',
            'Immobilier - Locaux Commerciaux & Bureaux',
            'Immobilier - Chalets & Propriétés de Vacances',
            'Immobilier - Colocations',

            'Électronique - Téléphones Cellulaires',
            'Électronique - Ordinateurs & PC',
            'Électronique - Tablettes',
            'Électronique - TV & Vidéo',
            'Électronique - Consoles de Jeux Vidéo',
            'Électronique - Appareils Photo & Caméscopes',
            'Électronique - Audio & Son',
            'Électronique - Électroménager',
            'Électronique - Wearables & Objets Connectés',
            'Électronique - Accessoires Électroniques',
            'Électronique - Autre Électronique',

            'Maison & Jardin - Meubles',
            'Maison & Jardin - Décoration',
            'Maison & Jardin - Cuisine & Vaisselle',
            'Maison & Jardin - Literie & Linge de Maison',
            'Maison & Jardin - Outillage & Bricolage',
            'Maison & Jardin - Jardinage & Extérieur',
            'Maison & Jardin - Chauffage & Climatisation',
            'Maison & Jardin - Luminaires',
            'Maison & Jardin - Articles de Nettoyage',

            'Mode & Beauté - Vêtements pour Femmes',
            'Mode & Beauté - Vêtements pour Hommes',
            'Mode & Beauté - Vêtements pour Enfants & Bébés',
            'Mode & Beauté - Chaussures',
            'Mode & Beauté - Bijoux & Montres',
            'Mode & Beauté - Sacs à Main & Accessoires',
            'Mode & Beauté - Maquillage & Soins Personnels',

            'Sports & Loisirs - Équipement Sportif',
            'Sports & Loisirs - Instruments de Musique',
            'Sports & Loisirs - Livres & Magazines',
            'Sports & Loisirs - Films, Musique & Jeux Vidéo (supports)',
            'Sports & Loisirs - Jeux & Jouets',
            'Sports & Loisirs - Articles de Collection',
            'Sports & Loisirs - Plein Air & Camping',
            'Sports & Loisirs - Voyage & Vacances',
            'Sports & Loisirs - Billets & Événements',

            'Services - Cours & Leçons',
            'Services - Déménagement & Transport',
            'Services - Nettoyage',
            'Services - Réparations & Dépannage',
            'Services - Services pour la Maison',
            'Services - Services Personnels',
            'Services - Services aux Entreprises',
            'Services - Soins & Garde d\'Animaux',
            'Services - Informatique & Web',

            'Animaux - Chiens',
            'Animaux - Chats',
            'Animaux - Oiseaux',
            'Animaux - Poissons & Aquariophilie',
            'Animaux - Petits Animaux',
            'Animaux - Accessoires pour Animaux',
            'Animaux - Élevage & Services Animaliers',

            'Bébé & Enfants - Vêtements',
            'Bébé & Enfants - Équipement de Puériculture',
            'Bébé & Enfants - Jouets',
            'Bébé & Enfants - Mobilier Enfant',
            'Bébé & Enfants - Livres & Matériel Éducatif',

            'Emploi - Offres d\'Emploi',
            'Emploi - Demandes d\'Emploi',
            'Emploi - Stages & Apprentissages',

            'Art & Collections - Œuvres d\'Art',
            'Art & Collections - Antiquités',
            'Art & Collections - Monnaies & Billets',
            'Art & Collections - Timbres',
            'Art & Collections - Objets de Collection Divers',

            'Matériaux & Outillage - Matériaux de Construction',
            'Matériaux & Outillage - Outils Électriques',
            'Matériaux & Outillage - Équipement Industriel',
            'Matériaux & Outillage - Équipement Agricole',

            'Autres - Articles Gratuits',
            'Autres - Échanges',
            'Autres - Coup de Main / Petits Boulots',
            'Autres - Perdu & Trouvé',
            'Autres - Tout le Reste',
        ];

        foreach ($categoriesToSeed as $description) {
            // firstOrCreate permet d'éviter les doublons si la catégorie existe déjà
            Categorie::firstOrCreate(['Description' => $description]);
        }
    }
}
