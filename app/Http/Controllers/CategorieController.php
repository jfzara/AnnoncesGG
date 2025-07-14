<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    // Liste toutes les catégories
    public function index()
    {
        $categories = Categorie::all();
        return view('categories.index', compact('categories'));
    }

    // Affiche le formulaire de création d'une catégorie
    public function create()
    {
        return view('categories.create');
    }

    // Enregistre une nouvelle catégorie
    public function store(Request $request)
    {
        // Validation ajustée pour utiliser 'Description' au lieu de 'NomCategorie'
        $request->validate([
            'Description' => 'required|string|max:20|unique:categories,Description',
        ]);

        // Crée la catégorie en utilisant la colonne 'Description'
        Categorie::create(['Description' => $request->input('Description')]);

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie créée avec succès.');
    }
    // Note : Les méthodes 'show', 'edit', 'update', 'destroy' sont manquantes
    // car vous n'avez pas encore demandé leur implémentation.
}
