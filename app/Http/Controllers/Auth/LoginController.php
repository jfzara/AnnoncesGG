<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utilisateur; // Utilisation du modèle Utilisateur

class LoginController extends Controller
{
    
    // Affiche le formulaire de login
    public function showLoginForm()
    {
        return view('auth.login'); // Vue par défaut du formulaire de login
    }
    
    
    public function login(Request $request)
    {
        // Récupérer les identifiants soumis
        $credentials = $request->only('Courriel', 'password');

        // Tentative d'authentification sans hachage
        if (Auth::attempt([
            'Courriel' => $credentials['Courriel'],
            'MotDePasse' => $credentials['password']
        ])) {
            // Rediriger si l'utilisateur est authentifié
            return redirect()->intended('dashboard');
        }

        // Si l'authentification échoue
        return back()->withErrors([
            'Courriel' => 'Les informations d\'identification ne correspondent pas.',
        ]);
    }
}