<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UnreadMessageCountComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            // C'est ici que l'erreur se produit
            // La ligne incorrecte était probablement : $unreadCount = Auth::user()->unreadMessages()->count();
            // Ou même : $unreadCount = Auth::user()->unreadMessages();

            // Correction : Appeler la méthode unreadMessagesCount() qui retourne directement le nombre.
            $unreadCount = Auth::user()->unreadMessagesCount(); // Ligne 14
        } else {
            $unreadCount = 0;
        }

        $view->with('unreadCount', $unreadCount);
    }
}
