<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogUserLogin
{
    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event): void
    {
        // Insérer une entrée dans la table connexions
        DB::table('connexions')->insert([
            'NoUtilisateur' => $event->user->NoUtilisateur,
            'Connexion' => Carbon::now(),
            'Deconnexion' => null, // À remplir lors de la déconnexion
        ]);
    }
}