<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route d'accueil publique (accessible à tous)
Route::get('/', function () {
    return view('welcome'); // Vue de la page d'accueil par défaut de Laravel
})->name('home');

// Routes d'authentification (connexion et déconnexion)
Route::get('/login', [CustomAuthController::class, 'login'])->name('login');
Route::post('/login', [CustomAuthController::class, 'loginPost'])->name('login.post');
Route::post('/logout', [CustomAuthController::class, 'logout'])->name('logout');

// Routes pour l'inscription
Route::get('/register', [CustomAuthController::class, 'register'])->name('register');
Route::post('/register', [CustomAuthController::class, 'registerPost'])->name('register.post');

// Routes protégées par l'authentification (nécessitent d'être connecté)
Route::middleware('auth')->group(function () {
    // CORRECTION : Nouvelle route pour la page des annonces (votre page d'accueil après connexion)
    // Cette route appelle la vue 'ListeAnnonces'
    Route::get('/annonces', function () {
        return view('ListeAnnonces');
    })->name('annonces.list'); // Nommez cette route 'annonces.list' pour la cohérence

    // Routes pour le profil utilisateur
    Route::get('/profile/edit', [CustomAuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CustomAuthController::class, 'updateProfile'])->name('profile.update');

    // Ajoutez ici d'autres routes qui nécessitent une authentification
});
