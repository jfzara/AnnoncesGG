<?php

namespace App\Http\Controllers;

use App\Models\Connexion;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class ConnexionController extends Controller
{
    // Liste les connexions d'un utilisateur spécifique
    public function index($utilisateurId)
    {
        $utilisateur = Utilisateur::find($utilisateurId);
        $connexions = $utilisateur->connexions;
        return view('connexions.index', compact('connexions', 'utilisateur'));
    }

    // Enregistre une nouvelle connexion (par exemple, lors de la connexion de l'utilisateur)
    public function store(Request $request)
    {
        $request->validate([
            'NoUtilisateur' => 'required|exists:utilisateurs,NoUtilisateur',
            'IP' => 'required|ip',
        ]);

        Connexion::create($request->all());
        return redirect()->back()->with('success', 'Connexion enregistrée.');
    }
}
