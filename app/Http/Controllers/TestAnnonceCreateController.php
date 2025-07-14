<?php

namespace App\Http\Controllers;

use App\Models\Categorie; // Assurez-vous que ce modèle est bien présent
use Illuminate\Http\Request;

class TestAnnonceCreateController extends Controller
{
    public function create()
    {
        try {
            // Tentative de récupérer les catégories
            $categories = Categorie::all();
            // Si la récupération réussit, passez les catégories à la vue
            return view('annonces.create', compact('categories'));
        } catch (\Exception $e) {
            // Si une erreur se produit (ex: table catégories non trouvée)
            // dd("Erreur lors de la récupération des catégories : " . $e->getMessage());
            // Pour l'instant, retournez une vue d'erreur simple ou un message
            return "Erreur : Impossible de charger les catégories. " . $e->getMessage();
        }
    }
}
