<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Routes pour les pages statiques
Route::get('/liste-annonces', function () {
    return view('ListeAnnonces');
});

Route::get('/gestion-annonces', function () {
    return view('GestionAnnonces');
});

Route::get('/mise-a-jour-profil', function () {
    return view('miseAJourProfil');
});

// Authentification (génère toutes les routes nécessaires pour l'authentification)
Auth::routes(); 

// Route personnalisée pour la connexion (post)
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Route personnalisée pour la déconnexion (post)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Page d'accueil protégée par le middleware auth
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');