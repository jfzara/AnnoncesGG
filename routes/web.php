<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthController;
use Illuminate\Support\Facades\Auth; // N'oubliez pas d'importer Auth si ce n'est pas déjà fait

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route d'accueil publique : redirige vers les annonces si connecté, sinon affiche la page de bienvenue
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('annonces.list');
    }
    return view('welcome');
})->name('home');

// Routes d'authentification (connexion et déconnexion)
Route::get('/login', [CustomAuthController::class, 'login'])->name('login');
Route::post('/login', [CustomAuthController::class, 'loginPost'])->name('login.post');
Route::post('/logout', [CustomAuthController::class, 'logout'])->name('logout');

// Routes pour l'inscription
Route::get('/register', [CustomAuthController::class, 'register'])->name('register');
Route::post('/register', [CustomAuthController::class, 'registerPost'])->name('register.post');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // La vraie page d'accueil après connexion : Liste des Annonces
    Route::get('/annonces', function () {
        return view('ListeAnnonces');
    })->name('annonces.list');

    // Route pour la gestion des annonces de l'utilisateur
    Route::get('/gestion-annonces', function () {
        return view('GestionAnnonces'); // Charger la vue GestionAnnonces.blade.php
    })->name('gestion-annonces');

    // Route pour la modification du profil utilisateur
    // Cette route utilise votre vue MiseAJourProfil.blade.php
    Route::get('/mise-a-jour-profil', [CustomAuthController::class, 'editProfile'])->name('profile.edit');
    // Assurez-vous que la méthode editProfile dans CustomAuthController retourne bien view('MiseAJourProfil')
    Route::put('/profile', [CustomAuthController::class, 'updateProfile'])->name('profile.update');

    // Ajoutez ici d'autres routes qui nécessitent une authentification
    // Exemple pour Annonce.php:
    // Route::get('/annonce/{id}', [AnnonceController::class, 'show'])->name('annonce.show');
    // Vous auriez besoin d'un AnnonceController et d'une vue Annonce.blade.php pour cela.
});
