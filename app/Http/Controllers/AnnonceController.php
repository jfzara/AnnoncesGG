<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Categorie;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    // Liste toutes les annonces
    public function index()
    {
        $annonces = Annonce::all();
        return view('annonces.index', compact('annonces'));
    }

    // Affiche le formulaire de création d'une annonce
    public function create()
    {
        $categories = Categorie::all();
        $utilisateurs = Utilisateur::all();
        return view('annonces.create', compact('categories', 'utilisateurs'));
    }

    // Enregistre une nouvelle annonce
    public function store(Request $request)
    {
        $request->validate([
            'Titre' => 'required',
            'Description' => 'required',
            'Categorie' => 'required|exists:categories,NoCategorie',
            'NoUtilisateur' => 'required|exists:utilisateurs,NoUtilisateur',
        ]);

        Annonce::create($request->all());
        return redirect()->route('annonces.index')
                         ->with('success', 'Annonce créée avec succès.');
    }

    // Affiche une annonce spécifique
    public function show($id)
    {
        $annonce = Annonce::find($id);
        return view('annonces.show', compact('annonce'));
    }

    // Supprime une annonce
    public function destroy($id)
    {
        Annonce::find($id)->delete();
        return redirect()->route('annonces.index')
                         ->with('success', 'Annonce supprimée avec succès.');
    }
}