<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController; 

Route::get('/liste-annonces', function () {
    return view('ListeAnnonces');
});

Route::get('/gestion-annonces', function () {
    return view('GestionAnnonces');
});

Route::get('/mise-a-jour-profil', function () {
    return view('miseAJourProfil');
});

// Routes de connexion
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');