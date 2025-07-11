<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UnreadMessageCountComposer
{
    public function compose(View $view)
    {
        $unreadMessageCount = 0;
        if (Auth::check()) {
            $unreadMessageCount = Auth::user()->unreadMessages()->count();
        }
        $view->with('unreadMessageCount', $unreadMessageCount);
    }
}
