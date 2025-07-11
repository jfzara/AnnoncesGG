<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Utilisation du modèle User standard de Laravel
use Illuminate\Validation\Rule;

class CustomAuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function login()
    {
        // Si l'utilisateur est déjà connecté, redirigez-le vers la page des annonces
        if (Auth::check()) {
            return redirect()->route('annonces.index');
        }
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
        ], [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Tente de connecter l'utilisateur avec les informations fournies
        // Auth::attempt utilise les champs 'email' et 'password' par défaut
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Si la connexion réussit, redirige l'utilisateur vers la page prévue ou vers les annonces.
            return redirect()->intended(route('annonces.index'));
        }

        // Si la connexion échoue, retourne à la page précédente avec une erreur.
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

        // Redirige l'utilisateur vers la page de login après la déconnexion
        return redirect()->route('login');
    }

    /**
     * Affiche le formulaire d'inscription.
     */
    public function register()
    {
        // Si l'utilisateur est déjà connecté, redirigez-le vers la page des annonces
        if (Auth::check()) {
            return redirect()->route('annonces.index');
        }
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
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Redirige l'utilisateur vers la page des annonces après une inscription réussie
        return redirect()->route('annonces.index')->with('success', 'Votre compte a été créé avec succès et vous êtes connecté !');
    }

    /**
     * Affiche le formulaire de modification de profil.
     */
    public function editProfile()
    {
        return view('MiseAJourProfil'); // Assurez-vous que cette vue existe bien
    }

    /**
     * Traite la soumission du formulaire de modification de profil.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Ajout de règles de validation pour les champs de profil
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ];

        // Ajouter les règles de mot de passe uniquement si le mot de passe actuel est fourni
        if ($request->filled('current_password')) {
            $rules['current_password'] = ['required', 'string', 'min:8', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Le mot de passe actuel est incorrect.');
                }
            }];
            $rules['password'] = 'required|string|min:8|confirmed';
        } elseif ($request->filled('password')) {
            // Si un nouveau mot de passe est fourni sans l'ancien, c'est une erreur.
            return back()->withErrors(['current_password' => 'Veuillez fournir votre mot de passe actuel pour modifier le mot de passe.']);
        }


        $request->validate($rules, [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée par un autre compte.',
            'current_password.required' => 'Veuillez entrer votre mot de passe actuel pour enregistrer les modifications.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins :min caractères.',
            'password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
