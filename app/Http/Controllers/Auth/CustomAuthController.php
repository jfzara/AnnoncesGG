<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    public function login(Request $request)
{
    // Validation des données de connexion
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Vérifiez si l'utilisateur existe
    $user = \DB::table('utilisateurs')->where('Courriel', $request->email)->first();

    // Vérifiez si l'utilisateur existe et que le mot de passe correspond
    if ($user && $user->MotDePasse === $request->password) {
        // Connectez l'utilisateur
        Auth::loginUsingId($user->NoUtilisateur);
        
        // Redirection vers la page d'accueil si la connexion réussit
        return redirect()->intended('/home');
    }

    // Redirection vers le formulaire de connexion avec un message d'erreur
    return back()->withErrors([
        'email' => 'Les informations d\'identification fournies sont incorrectes.',
    ]);
}

    public function logout()
{
    Auth::logout();
    return redirect('/login')->with('success', 'Vous êtes déconnecté avec succès.');
}

}
