<?php

namespace Database\Seeders;

use App\Models\Annonce;
use App\Models\User;
use App\Models\Categorie; // Assurez-vous d'importer Categorie aussi pour la vérification
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnonceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Vider la table des annonces pour un re-seeding propre
        Annonce::truncate();
        $this->command->info("Table 'annonces' vidée.");


        // 2. Vos URLs d'images Cloudinary
        $cloudinaryImageUrls = [
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630523/Voituresport_obfii5.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630522/Voiture_compacte_onipx7.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630521/Tracteur_zh9pfk.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630520/Terrain_constructible_fcd7dv.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630518/Téléviseur_grand_écran_zz6e9y.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630517/Tablette_numérique_kuxqtr.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630516/Système_home_cinéma_i1unms.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630515/SUV_fpsjid.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630515/Souris_d_ordinateur_lpqdl8.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630512/Smartphone_écran_allumé_iudtnw.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630487/Salon_spacieux_mmkgme.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630482/Salle_de_bain_d_appartement_jwzfna.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630481/Robot_de_cuisine_tec6z8.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630481/Roulotte_jzwznu.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630479/Réfrigérateur_s7yaet.jpg',
            'https://res.com/dn2wdozm9/image/upload/v1752630478/QuadVTT_cnm2yk.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630478/Propriété_de_vacances_au_bord_de_l_eau_ejwthd.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630449/Pneu_de_voiture_jgyom3.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630441/Ordinateur_portable_xbeijf.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630441/Ordinateur_de_bureau_d3am7h.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630439/Moto_de_route_jwjasu.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630438/Montre_connectée_yawlnz.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630438/Manette_de_jeu_ciref3.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630437/Maison_avec_jardin_extérieur_jfobpk.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630436/Local_commercial_vitré_naqf1z.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630435/Lave-linge_qjtl1k.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630435/Jet_ski_clwumi.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630434/Jante_de_véhicule_dykwit.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630433/Fourgon_utilitaire_eslurd.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630433/Espace_commun_en_colocation_pxtngy.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630432/Enceinte_Bluetooth_shggnz.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630431/Écouteurs_sans_fil_n5cw7k.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630431/Cuisine_d_appartement_ytp4gv.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630430/Console_de_jeux_fwlaee.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630430/Clavier_ukuyg1.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630429/Chambre_meublée_à_louer_fpfwmq.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630429/Chambre_à_coucher_bjk5bz.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630428/Chalet_en_montagne_eiuu18.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630427/Casque_de_moto_m0srsb.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630427/Casque_audio_hwqs82.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630427/Camping-car_w9rqor.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630427/Camionnette_mq0erd.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630425/Bureau_moderne_uttfeo.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630424/Berline_familiale_u0uzsx.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630424/Bateau_de_plaisance_pn3e8o.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630424/Appartement_moderne_intérieur_hw0kat.jpg',
            'https://res.cloudinary.com/dn2wdozm9/image/upload/v1752630423/Appareil_photo_reflex_fkrytx.jpg',
        ];

        // 3. Récupère TOUS les utilisateurs EXISTANTS
        $users = User::all();
        $this->command->info("Nombre d'utilisateurs trouvés : " . $users->count());

        if ($users->isEmpty()) {
            $this->command->error("ERREUR : Aucun utilisateur trouvé pour créer des annonces. Assurez-vous que UserSeeder est exécuté AVANT AnnonceSeeder.");
            return; // Arrête l'exécution si aucun utilisateur n'est là
        }

        // 4. Vérifie les catégories
        $categoriesCount = Categorie::count();
        $this->command->info("Nombre de catégories trouvées : " . $categoriesCount);
        if ($categoriesCount === 0) {
            $this->command->error("ERREUR : Aucune catégorie trouvée. Assurez-vous que CategorieSeeder est exécuté AVANT AnnonceSeeder.");
            return;
        }

        $annoncesPerUser = 5; // Nombre d'annonces par utilisateur
        $imageCount = count($cloudinaryImageUrls);
        $imageIndex = 0; // Pour parcourir les images de manière cyclique
        $totalAnnoncesCreated = 0;

        // 5. Boucle sur chaque utilisateur pour créer leurs 5 annonces
        foreach ($users as $user) {
            $this->command->info("Création d'annonces pour l'utilisateur ID: " . $user->id . " (" . $user->name . ")");
            for ($i = 0; $i < $annoncesPerUser; $i++) {
                try {
                    Annonce::factory()->create([
                        'NoUtilisateur' => $user->id,
                        'Photo' => $cloudinaryImageUrls[$imageIndex],
                    ]);
                    $totalAnnoncesCreated++;
                    $this->command->info(" - Annonce " . ($i + 1) . " créée pour l'utilisateur " . $user->id);

                    // Passe à l'image suivante, en revenant au début si la fin est atteinte
                    $imageIndex = ($imageIndex + 1) % $imageCount;

                } catch (\Exception $e) {
                    $this->command->error("ERREUR lors de la création de l'annonce pour l'utilisateur " . $user->id . ", tentative " . ($i + 1) . ": " . $e->getMessage());
                    // Optionnel: Si vous voulez arrêter complètement le seeder à la première erreur, décommentez la ligne ci-dessous
                    // return;
                }
            }
        }
        $this->command->info("Total final : " . Annonce::count() . " annonces ont été créées avec des images Cloudinary.");
        if (Annonce::count() == 0) {
            $this->command->error("ATTENTION : Aucune annonce n'a été créée. Vérifiez les logs d'erreurs ci-dessus ou les contraintes de votre base de données.");
        }
    }
}
