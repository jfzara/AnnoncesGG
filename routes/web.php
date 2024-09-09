<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthUserController; 
use Illuminate\Support\Facades\Auth;

// Routes pour les pages statiques
Route::get('/liste-annonces', function () {
    return view('ListeAnnonces'); // Assurez-vous que le fichier de vue existe
});

Route::get('/gestion-annonces', function () {
    return view('GestionAnnonces'); // Assurez-vous que le fichier de vue existe
});

Route::get('/mise-a-jour-profil', function () {
    return view('miseAJourProfil'); // Assurez-vous que le fichier de vue existe
});

// Authentification (si tu souhaites utiliser les routes par défaut, laisse cette ligne)
Auth::routes(); // Cette ligne génère toutes les routes nécessaires pour l'authentification

// Routes personnalisées pour le contrôleur d'authentification
Route::post('/login', [AuthUserController::class, 'login'])->name('login');
Route::post('/logout', [AuthUserController::class, 'logout'])->name('logout');

// Page d'accueil
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');