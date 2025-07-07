<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\AnnonceController;
use Illuminate\Support\Facades\Auth;

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

// Routes publiques pour afficher toutes les annonces (même pour les non-connectés)
Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.list');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Route pour la gestion des annonces de l'utilisateur (tableau de bord)
    Route::get('/gestion-annonces', [AnnonceController::class, 'gererAnnonces'])->name('gestion-annonces');

    // Routes CRUD pour les annonces (création, édition, suppression, etc.)
    // La méthode 'resource' crée automatiquement les routes pour create, store, edit, update, destroy.
    // 'except(['index', 'show'])' signifie que les routes index et show ne sont PAS protégées par auth,
    // car elles sont publiques (définies plus haut).
    Route::resource('annonces', AnnonceController::class)->except(['index', 'show']);

    // Routes pour la modification du profil utilisateur (si vous les avez)
    Route::get('/mise-a-jour-profil', [CustomAuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CustomAuthController::class, 'updateProfile'])->name('profile.update');

    // Ajoutez ici d'autres routes qui nécessitent une authentification
});

// Route publique pour l'affichage d'une annonce spécifique (accessible à tous)
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');
