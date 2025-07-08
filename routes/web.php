<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\TestAnnonceCreateController; // <-- C'EST ICI
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route d'accueil publique : redirige vers les annonces si connecté, sinon affiche la page de bienvenue
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('annonces.index');
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

// Routes publiques pour les Annonces
Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Route pour la gestion des annonces de l'utilisateur (tableau de bord)
    Route::get('/gestion-annonces', [AnnonceController::class, 'gestionAnnonces'])->name('annonces.gestion');

    // Routes CRUD pour les annonces
    // Attention : la route 'annonces.create' est déjà définie implicitement ici
    // par Route::resource('annonces', AnnonceController::class)
    // qui gère aussi la méthode create du contrôleur AnnonceController.
    // Assurez-vous que cette route de test ne crée pas de conflit si vous réactivez l'AnnonceController.
    Route::resource('annonces', AnnonceController::class)->except(['index', 'show']);

    // Routes pour la modification du profil utilisateur (si vous les avez)
    Route::get('/mise-a-jour-profil', [CustomAuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CustomAuthController::class, 'updateProfile'])->name('profile.update');

    // Routes pour la gestion des catégories
    Route::resource('categories', CategorieController::class);

    // Ajoutez ici d'autres routes qui nécessitent une authentification

    // Nouvelle route de test pour la création d'annonce (CORRECTEMENT PLACÉE DANS LE GROUPE "auth")
    Route::get('/test-annonces-create', [TestAnnonceCreateController::class, 'create'])->name('test.annonces.create');

});
