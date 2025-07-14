<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\CategorieController; // Assurez-vous d'importer CategorieController
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Nouvelle Route d'accueil : redirige TOUJOURS vers la page de connexion si non authentifié.
// Si déjà authentifié, on se fie à la redirection post-login qui est gérée par le contrôleur.
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('annonces.index'); // Si déjà connecté, va directement aux annonces
    }
    return redirect()->route('login'); // Sinon, redirige vers la page de login
})->name('home');

// Routes d'authentification (connexion et inscription)
Route::get('/login', [CustomAuthController::class, 'login'])->name('login');
Route::post('/login', [CustomAuthController::class, 'loginPost'])->name('login.post');
Route::get('/register', [CustomAuthController::class, 'register'])->name('register');
Route::post('/register', [CustomAuthController::class, 'registerPost'])->name('register.post');

// Groupe de routes pour les utilisateurs AUTHENTIFIÉS (normaux et admin)
Route::middleware('auth')->group(function () {

    // Route de déconnexion
    Route::post('/logout', [CustomAuthController::class, 'logout'])->name('logout');

    // Routes CRUD complètes pour les annonces (inclut 'create', 'store', 'edit', 'update', 'destroy')
    Route::resource('annonces', AnnonceController::class)->except(['index', 'show']);

    // Route pour la gestion des annonces de l'utilisateur (tableau de bord personnel)
    Route::get('/gestion-annonces', [AnnonceController::class, 'gestionAnnonces'])->name('annonces.gestion');

    // Routes pour la modification du profil utilisateur
    Route::get('/mise-a-jour-profil', [CustomAuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CustomAuthController::class, 'updateProfile'])->name('profile.update');

    // --- NOUVELLES ROUTES POUR LA MESSAGERIE ---
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{annonce}/{otherUser}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{annonce}/{otherUser}', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
    // ----------------------------------------
});

// --- NOUVEAU GROUPE DE ROUTES POUR LES ADMINISTRATEURS UNIQUEMENT ---
Route::middleware(['auth', 'admin'])->group(function () {
    // Routes pour la gestion des catégories (déplacées ici)
    Route::resource('categories', CategorieController::class);
    // Si vous avez d'autres fonctionnalités d'administration, ajoutez-les ici
});

// Routes Annonces publiques (accessibles à tous les visiteurs)
Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');

// Ces routes semblent être pour un formulaire de contact d'annonce.
// Si vous les avez remplacées par le système de messagerie, elles pourraient être supprimées ou ajustées.
Route::get('/annonces/{annonce}/contact', [App\Http\Controllers\ContactController::class, 'create'])->name('annonces.contact.create');
Route::post('/annonces/{annonce}/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('annonces.contact.send');
