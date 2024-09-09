<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utilisateur;

class AuthUserController extends Controller
{
    public function login(Request $request)
    {
        // Validation des données d'entrée avec contraintes sur le mot de passe
        $credentials = $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required|min:5|max:15|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/', // Lettres et chiffres combinés
        ]);

        // Récupérer l'utilisateur par email
        $user = Utilisateur::where('Courriel', $credentials['email'])->first();

        // Vérifier si l'utilisateur existe et a un statut confirmé
        if ($user && $user->Statut == 9) {
            // Essayer d'authentifier l'utilisateur
            if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                // Mettre à jour le nombre de connexions
                $user->NbConnexions = $user->NbConnexions + 1;
                $user->save();

                // Debug: Afficher que l'authentification a réussi
                \Log::info('Authentification réussie pour l\'utilisateur : ' . $credentials['email']);

                return redirect()->route('home')->with('success', 'Connexion réussie !');
            } else {
                // Debug: Afficher que l'authentification a échoué
                \Log::warning('Échec de l\'authentification pour l\'utilisateur : ' . $credentials['email']);
                return back()->withErrors([
                    'email' => 'Les informations de connexion sont incorrectes.',
                ]);
            }
        } else {
            // Gérer le cas où l'utilisateur n'existe pas ou n'est pas confirmé
            \Log::warning('Utilisateur non trouvé ou statut non confirmé : ' . $credentials['email']);
            return back()->withErrors([
                'email' => 'Votre compte n\'est pas confirmé ou n\'existe pas.',
            ]);
        }
    }
}