<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\CategorieController;
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

// Routes d'authentification (connexion et inscription)
Route::get('/login', [CustomAuthController::class, 'login'])->name('login');
Route::post('/login', [CustomAuthController::class, 'loginPost'])->name('login.post');
Route::get('/register', [CustomAuthController::class, 'register'])->name('register');
Route::post('/register', [CustomAuthController::class, 'registerPost'])->name('register.post');

// IMPORTANT : Les routes de ressource complètes pour 'annonces' AVEC le middleware 'auth' EN PREMIER.
// Cela garantit que '/annonces/create' est définie spécifiquement avant que '/annonces/{annonce}' ne soit vue.
Route::middleware('auth')->group(function () {

    // Route de déconnexion
    Route::post('/logout', [CustomAuthController::class, 'logout'])->name('logout');

    // Routes CRUD complètes pour les annonces (inclut 'create', 'store', 'edit', 'update', 'destroy')
    // Les routes 'index' et 'show' seront surchargées ou gérées par les définitions publiques ensuite.
    // L'ordre est crucial ici.
    Route::resource('annonces', AnnonceController::class);

    // Route pour la gestion des annonces de l'utilisateur (tableau de bord personnel)
    // Assurez-vous que cette route n'entre pas en conflit avec les routes de ressources.
    // Si 'annonces.gestion' est distinct de la ressource, elle est bien placée ici.
    Route::get('/gestion-annonces', [AnnonceController::class, 'gestionAnnonces'])->name('annonces.gestion');

    // Routes pour la modification du profil utilisateur
    Route::get('/mise-a-jour-profil', [CustomAuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CustomAuthController::class, 'updateProfile'])->name('profile.update');

    // Routes pour la gestion des catégories
    Route::resource('categories', CategorieController::class);

    // Routes pour le formulaire de contact spécifique à une annonce
    Route::get('/annonces/{annonce}/contact', [App\Http\Controllers\ContactController::class, 'create'])->name('annonces.contact.create');
    Route::post('/annonces/{annonce}/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('annonces.contact.send');
});


// Routes Annonces publiques (accessibles à tous les visiteurs)
// Définies APRÈS les routes de ressources pour éviter les conflits avec '/create' ou '/{id}/edit' etc.
// Ces routes doivent être placées après toutes les routes de ressources qui ont des segments fixes.
Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');
