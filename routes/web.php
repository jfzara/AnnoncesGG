<?php

use Illuminate\Support\Facades\Route;

Route::get('/liste-annonces', function () {
    return view('ListeAnnonces');
});

Route::get('/gestion-annonces', function () {
    return view('GestionAnnonces');
});

Route::get('/mise-a-jour-profil', function () {
    return view('miseAJourProfil');
});