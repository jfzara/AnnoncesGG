<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;

class CustomAuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::loginUsingId($user->id);
            // Redirige l'utilisateur vers la page des annonces après une connexion réussie
            return redirect()->intended(route('annonces.list'));
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies sont incorrectes.',
        ])->onlyInput('email');
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Affiche le formulaire d'inscription.
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Traite la soumission du formulaire d'inscription.
     */
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Redirige l'utilisateur vers la page des annonces après une inscription réussie
        return redirect()->route('annonces.list')->with('success', 'Votre compte a été créé avec succès et vous êtes connecté !');
    }

    /**
     * Affiche le formulaire de modification de profil.
     */
    public function editProfile()
    {
        // Correction : Utilise le nom de la vue correcte "MiseAJourProfil"
        return view('MiseAJourProfil');
    }

    /**
     * Traite la soumission du formulaire de modification de profil.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|string|min:8',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password') || ($request->name !== $user->name || $request->email !== $user->email)) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
