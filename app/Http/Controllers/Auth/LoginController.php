<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Vérifiez si l'utilisateur existe
    $user = \DB::table('utilisateurs')->where('Courriel', $request->email)->first();

    // Vérifiez si l'utilisateur existe et que le mot de passe correspond
    if ($user && $user->MotDePasse === $request->password) { // Comparaison directe
        // Connectez l'utilisateur
        Auth::loginUsingId($user->NoUtilisateur);
        
        if (Auth::check()) {
            \Log::info('Utilisateur authentifié après la connexion: ' . Auth::user()->Courriel);
        } else {
            \Log::warning('L\'utilisateur n\'est pas authentifié après la tentative de connexion.');
        }

        // Message de débogage pour confirmer la connexion
        \Log::info('Utilisateur connecté: ' . $user->Courriel);
        
        return redirect()->intended('/home');
    }

    // Message de débogage en cas d'échec
    \Log::warning('Échec de la connexion pour l\'utilisateur: ' . $request->email);
    
    return back()->withErrors([
        'email' => 'Les informations d\'identification fournies sont incorrectes.',
    ]);
}

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}