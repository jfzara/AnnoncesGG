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
        $request->validate([
            'NomCategorie' => 'required|unique:categories,NomCategorie',
        ]);

        Categorie::create($request->all());
        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie créée avec succès.');
    }
}