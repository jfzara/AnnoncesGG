<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\MessageController;
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

    // Routes pour la gestion des catégories
    Route::resource('categories', CategorieController::class);

    // --- NOUVELLES ROUTES POUR LA MESSAGERIE ---
    // Route pour afficher toutes les conversations de l'utilisateur (Inbox)
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');

    // Route pour afficher une conversation spécifique.
    // Utilise le Route Model Binding pour 'annonce' et 'otherUser'.
    Route::get('/messages/{annonce}/{otherUser}', [MessageController::class, 'show'])->name('messages.show');

    // Route pour envoyer un message.
    // Elle nécessite l'ID de l'annonce et l'ID du destinataire (otherUser).
    // La méthode store prendra Annonce $annonce et User $otherUser.
    Route::post('/messages/{annonce}/{otherUser}', [MessageController::class, 'store'])->name('messages.store');

    // Route pour marquer un message comme lu (peut être utile pour AJAX)
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
    // ----------------------------------------
});


// Routes Annonces publiques (accessibles à tous les visiteurs)
Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');

// Ces routes semblent être pour un formulaire de contact d'annonce.
// Si vous les avez remplacées par le système de messagerie, elles pourraient être supprimées ou ajustées.
Route::get('/annonces/{annonce}/contact', [App\Http\Controllers\ContactController::class, 'create'])->name('annonces.contact.create');
Route::post('/annonces/{annonce}/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('annonces.contact.send');

// Si vous utilisez les routes d'authentification de Laravel Breeze/UI
// require __DIR__.'/auth.php';
