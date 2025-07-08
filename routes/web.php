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
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
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
// !!! IMPORTANT : Placez les routes spécifiques avant les routes génériques avec paramètres.
// annonces.create est plus spécifique que annonces.show
Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');

// Route explicite pour la création d'une annonce (doit être AVANT {annonce})
Route::get('/annonces/create', [AnnonceController::class, 'create'])->name('annonces.create');

// Route explicite pour le stockage d'une nouvelle annonce
Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');

// Route pour afficher une annonce spécifique (paramètre {annonce})
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');


// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Route pour la gestion des annonces de l'utilisateur (tableau de bord)
    Route::get('/gestion-annonces', [AnnonceController::class, 'gestionAnnonces'])->name('annonces.gestion');

    // Routes CRUD pour les annonces
    // Pour ce test, nous laissons Route::resource définir TOUTES les routes.
    // L'ordre devrait faire que les routes explicites ci-dessus soient prises en compte en premier.
    Route::resource('annonces', AnnonceController::class); // <-- Plus d'except() ici

    // Routes pour la modification du profil utilisateur (si vous les avez)
    Route::get('/mise-a-jour-profil', [CustomAuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CustomAuthController::class, 'updateProfile'])->name('profile.update');

    // Routes pour la gestion des catégories
    Route::resource('categories', CategorieController::class);
    // Route pour afficher le formulaire de contact sur la page de détail d'une annonce
Route::get('/annonces/{annonce}/contact', [App\Http\Controllers\ContactController::class, 'create'])->name('annonces.contact.create');

// Route pour traiter l'envoi du formulaire de contact
Route::post('/annonces/{annonce}/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('annonces.contact.send');

});
