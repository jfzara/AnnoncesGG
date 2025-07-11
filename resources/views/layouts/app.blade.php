<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TrouveTout') }} - @yield('title', 'Accueil')</title>

    {{-- Liens CSS --}}
    {{-- Mise à jour vers Bootstrap 5.3.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- Mise à jour vers Font Awesome 6.5.2 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Vos styles CSS personnalisés --}}
    @yield('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'TrouveTout') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
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
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto">
                    @auth
                        {{-- Lien vers les messages avec indicateur de non lus --}}
                        <li class="nav-item me-3">
                            <a class="nav-link position-relative" href="{{ route('messages.index') }}">
                                <i class="fas fa-envelope fa-lg"></i> Messages
                                @php
                                    // Assurez-vous que Auth::user() existe et que la méthode unreadMessagesCount() est définie dans le modèle User
                                    $unreadCount = Auth::user() ? Auth::user()->unreadMessagesCount() : 0;
                                @endphp
                                @if ($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unreadCount }}
                                        <span class="visually-hidden">nouveaux messages non lus</span>
                                    </span>
                                @endif
                            </a>
                        </li>

                        {{-- Menu déroulant pour l'utilisateur connecté --}}
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="color: yellow;">
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Modifier le profil</a>
                                <a class="dropdown-item" href="{{ route('messages.index') }}">
                                    Boîte de réception
                                    @if ($unreadCount > 0)
                                        <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                                    @endif
                                </a>
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
                    @else
                        {{-- Liens pour les utilisateurs non connectés --}}
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    {{-- Scripts JS --}}
    {{-- ATTENTION : LA VALEUR DE L'INTEGRITY ATTRIBUTE A ÉTÉ CORRIGÉE --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @yield('scripts')
</body>
</html>
