<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    // Liste tous les utilisateurs
    public function index()
    {
        $utilisateurs = Utilisateur::all();
        return view('utilisateurs.index', compact('utilisateurs'));
    }

    // Affiche le formulaire de création d'un utilisateur
    public function create()
    {
        return view('utilisateurs.create');
    }

    // Enregistre un nouvel utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'Courriel' => 'required|email',
            'MotDePasse' => 'required|min:6',
            'Nom' => 'required',
            'Prenom' => 'required',
        ]);

        Utilisateur::create($request->all());
        return redirect()->route('utilisateurs.index')
                         ->with('success', 'Utilisateur créé avec succès.');
    }

    // Affiche un utilisateur spécifique
    public function show($id)
    {
        $utilisateur = Utilisateur::find($id);
        return view('utilisateurs.show', compact('utilisateur'));
    }

    // Affiche le formulaire d'édition
    public function edit($id)
    {
        $utilisateur = Utilisateur::find($id);
        return view('utilisateurs.edit', compact('utilisateur'));
    }

    // Met à jour un utilisateur
    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::find($id);
        $utilisateur->update($request->all());
        return redirect()->route('utilisateurs.index')
                         ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    // Supprime un utilisateur
    public function destroy($id)
    {
        Utilisateur::find($id)->delete();
        return redirect()->route('utilisateurs.index')
                         ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
