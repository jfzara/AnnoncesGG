<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnnoncesGG - @yield('title', 'Accueil')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40hNPFNmhMIUTweLLgByJTM53jlkp0EZSCIDUPgKqgNHPDwzthHxpsiphon2WMjY/X7OQXQd/Jsw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Vous pouvez ajouter vos propres styles CSS ici --}}
    @yield('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">AnnoncesGG</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('annonces.index') ? 'active' : '' }}" href="{{ route('annonces.index') }}">Annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">Catégories</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('annonces.gestion') ? 'active' : '' }}" href="{{ route('annonces.gestion') }}">Mes Annonces</a>
                    </li>
                    {{-- Si vous avez des pages de gestion admin des catégories, ce serait ici --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('categories.create') ? 'active' : '' }}" href="{{ route('categories.create') }}">Ajouter Catégorie</a>
                    </li> --}}
                    @endauth
                </ul>

                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                {{-- Ajout du lien vers l'édition de profil --}}
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Modifier le profil</a>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Déconnexion
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLCPfmXh+bttJGrkwrxVpvHkLAsWwEH0EcY1qX7vX/tLkXigyoV+WwY/L+Vj" crossorigin="anonymous"></script>

    @yield('scripts')
</body>
</html>
