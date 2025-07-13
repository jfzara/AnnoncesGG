<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TrouveTout') }} - @yield('title', 'Accueil')</title>

    {{-- Liens CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Google Fonts pour des polices modernes et lisibles (Space Mono pour titres, Inter pour corps) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">


    {{-- Styles CSS personnalisés globaux --}}
    <style>
        :root {
            /* Palette de couleurs Brutaliste & Fonctionnelle */
            --primary-dark: #1a1a1aff; /* Noir très profond pour les fonds principaux et textes clés */
            --secondary-dark: #333333; /* Gris foncé pour les éléments secondaires */
            --accent-red-orange: #f84200ff; /* Rouge-orange vif et unique pour l'accentuation cruciale */
            --light-pure-white: #FFFFFF; /* Blanc pur pour les fonds et textes sur sombre */
            --text-on-light: #1A1A1A; /* Texte noir sur fonds clairs */
            --text-on-dark: #FFFFFF; /* Texte blanc sur fonds sombres */
            --border-strong: #1A1A1A; /* Bordures nettes et sombres */
            --border-subtle: #EEEEEE; /* Bordure très claire pour un aspect aéré */

            /* Aucun dégradé pour le brutaliste, couleurs unies */
            --header-background: var(--primary-dark);
            --button-background: var(--accent-red-orange);
            --button-hover-background: #E63900; /* Un rouge-orange légèrement plus foncé au survol */

            /* Couleurs de feedback - fortes et directes */
            --success-color: #28A745; /* Vert standard, clair et visible */
            --danger-color: #DC3545; /* Rouge standard, clair et visible */
            --warning-color: #FFC107; /* Jaune standard, clair et visible */
            --info-color: #17A2B8; /* Bleu standard, clair et visible */

            /* Variables de lisibilité et UX */
            --focus-ring-color: rgba(255, 69, 0, 0.6); /* Basé sur l'accent rouge-orange */
            --link-hover-color: var(--accent-red-orange);
            --placeholder-color: #666666; /* Gris neutre pour le placeholder */

            /* Ombres minimalistes ou absentes */
            --shadow-none: none;
            --shadow-subtle: 0 1px 3px rgba(0, 0, 0, 0.05); /* Ombre très légère et douce */

            /* Polices Brutalistes */
            --font-heading: 'Space Mono', monospace; /* Police monospace pour un look technique et audacieux */
            --font-body: 'Inter', sans-serif; /* Police sans empattement pour la lisibilité du corps */

            /* Variables pour la discussion / chat (ajustées au nouveau thème) */
            --chat-bg-my-message: #F0F0F0; /* Gris très clair pour les messages envoyés */
            --chat-bg-other-message: var(--light-pure-white); /* Blanc pur pour les messages reçus */
            --chat-text-color: var(--text-on-light);
            --chat-timestamp-color: #555555;
            --chat-read-icon-color: var(--success-color);
            --chat-unread-icon-color: #AAAAAA;
            --chat-border-color: var(--border-subtle); /* Bordure très claire pour le chat */
            --chat-background: var(--light-pure-white);
        }

        /* --- Généralités et accessibilité --- */
        html {
            overflow-x: hidden; /* Empêche le scroll horizontal indésirable */
        }
        body {
            font-family: var(--font-body);
            color: var(--text-on-light);
            background-color: var(--light-pure-white); /* Fond principal blanc pur */
            line-height: 1.7; /* Légèrement plus d'interlignage pour l'aération */
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Assure qu'aucun débordement ne cause un scroll horizontal */
        }

        /* Adapter le container par défaut de Bootstrap pour être plus large sur les grands écrans */
        @media (min-width: 1400px) { /* Pour les très grands écrans (xxl de Bootstrap) */
            .container {
                max-width: 1500px; /* Ou une valeur qui vous semble appropriée pour votre contenu */
            }
        }
        @media (min-width: 1600px) { /* Pour des écrans encore plus larges */
            .container {
                max-width: 1680px; /* Exemple de largeur, ajustez selon vos besoins */
            }
        }
        /* Pour les "container-fluid", ils occuperont 100% naturellement */


        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            color: var(--primary-dark); /* Titres en noir très foncé */
            font-weight: 700; /* Gras pour l'impact */
            line-height: 1.2; /* Interlignage légèrement assoupli pour les titres */
            margin-bottom: 2rem; /* Espace généreux sous les titres pour aérer */
            text-transform: uppercase; /* Typo capitale pour le style brutaliste */
            letter-spacing: 0.04em; /* Espacement des lettres légèrement réduit pour la lisibilité */
        }
        h1 { font-size: 3.2rem; } /* Légèrement plus grand */
        h2 { font-size: 2.4rem; }
        h3 { font-size: 2rem; }
        h4 { font-size: 1.6rem; }
        h5 { font-size: 1.4rem; }
        h6 { font-size: 1.2rem; }


        p {
            margin-bottom: 1.2rem; /* Plus d'espace sous les paragraphes */
            font-size: 1.05rem; /* Légèrement plus grand pour la lisibilité */
        }

        a {
            color: var(--primary-dark); /* Liens par défaut en noir */
            text-decoration: none;
            transition: color 0.1s ease-in-out, text-decoration 0.1s ease-in-out;
        }

        a:hover {
            color: var(--link-hover-color); /* Liens accentués au survol */
            text-decoration: underline; /* Soulignement pour l'accessibilité */
        }

        /* Amélioration du focus pour l'accessibilité - bordure solide et visible */
        .btn:focus, .form-control:focus, .form-select:focus,
        a:focus:not(.btn) {
            outline: 2px solid var(--focus-ring-color); /* Bordure de focus forte */
            outline-offset: 2px; /* Décalage pour ne pas empiéter sur l'élément */
            box-shadow: var(--shadow-none); /* Pas d'ombre au focus */
            border-color: var(--focus-ring-color); /* Couleur de bordure accentuée */
        }

        /* --- Navbar --- */
        .navbar {
            background-color: var(--header-background); /* Couleur unie sombre */
            box-shadow: var(--shadow-none); /* Pas d'ombre sur la navbar */
            padding: 1.2rem 0; /* Plus de padding pour la présence et l'aération */
            border-bottom: 2px solid var(--border-strong); /* Bordure basse forte */
            border-radius: 0; /* Pas d'arrondis */
        }

        /* Le conteneur à l'intérieur de la navbar reste 'container' pour aligner le contenu */
        .navbar .container {
            max-width: none; /* Annule le max-width par défaut pour laisser le contenu de la navbar s'étendre */
            padding-left: 2rem; /* Padding des côtés pour les petits écrans */
            padding-right: 2rem;
        }
        /* Et on le redéfinit pour les grands écrans pour qu'il respecte le nouveau max-width du body */
        @media (min-width: 1400px) {
            .navbar .container {
                max-width: 1500px; /* Adapte la largeur au nouveau container général */
            }
        }
        @media (min-width: 1600px) {
            .navbar .container {
                max-width: 1680px;
            }
        }


        .navbar-brand {
            color: var(--text-on-dark) !important;
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 2.2rem; /* Plus grand pour l'impact */
            display: flex;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.06em; /* Plus d'espacement pour le logo */
        }
        .navbar-brand i {
            margin-right: 0.8rem; /* Plus d'espace entre icône et texte */
            color: var(--accent-red-orange); /* Icône en couleur d'accent */
            font-size: 2rem; /* Plus grand */
        }

        .navbar-nav .nav-link {
            color: var(--text-on-dark) !important;
            font-weight: 700; /* Gras */
            padding: 0.8rem 1.4rem; /* Padding généreux pour les liens */
            border-radius: 0; /* Pas d'arrondis */
            transition: all 0.1s ease-in-out;
            text-transform: uppercase; /* Texte en capitales */
            letter-spacing: 0.03em; /* Légèrement plus d'espacement */
        }

        .navbar-nav .nav-link:hover {
            color: var(--accent-red-orange) !important; /* Accentué au survol */
            background-color: var(--secondary-dark); /* Fond sombre au survol */
        }

        .navbar-nav .nav-link.active {
            color: var(--text-on-dark) !important;
            background-color: var(--accent-red-orange); /* Couleur d'accent pour l'actif */
            font-weight: 700;
            box-shadow: var(--shadow-none);
        }

        .navbar-nav .dropdown-toggle {
            color: var(--accent-red-orange) !important;
            font-weight: 700;
        }

        .dropdown-menu {
            background-color: var(--primary-dark); /* Fond du menu sombre */
            border: 2px solid var(--accent-red-orange); /* Bordure forte avec accent */
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            border-radius: 0; /* Pas d'arrondis */
            padding: 0; /* Pas de padding interne par défaut */
        }

        .dropdown-item {
            color: var(--text-on-dark) !important;
            padding: 0.9rem 1.5rem; /* Padding généreux pour les items */
            transition: background-color 0.1s ease, color 0.1s ease;
            text-transform: uppercase;
            font-size: 0.95rem; /* Légèrement plus grand */
            border-bottom: 1px solid rgba(255, 255, 255, 0.15); /* Ligne de séparation subtile mais visible */
        }
        .dropdown-item:last-child {
            border-bottom: none; /* Pas de bordure sur le dernier item */
        }

        .dropdown-item:hover {
            background-color: var(--secondary-dark); /* Gris foncé au survol */
            color: var(--accent-red-orange) !important; /* Texte accentué au survol */
        }

        .dropdown-item-danger:hover {
            background-color: var(--danger-color) !important;
            color: var(--text-on-dark) !important;
        }

        /* Badge pour les messages non lus dans la navbar */
        .navbar .badge.bg-danger {
            background-color: var(--accent-red-orange) !important;
            color: var(--text-on-dark); /* Texte blanc sur accent */
            font-size: 0.85rem; /* Plus grand et plus lisible */
            padding: 0.35em 0.65em; /* Plus de padding */
            border-radius: 0; /* Pas d'arrondis */
            transform: translate(15%, -45%); /* Ajuster position */
            min-width: 28px; /* Assure taille minimale */
            text-align: center;
        }

        /* --- Header de carte / éléments avec dégradé --- */
        .custom-gradient-header { /* Renommé en custom-header pour cohérence brutaliste */
            background-color: var(--primary-dark); /* Couleur unie sombre */
            color: var(--text-on-dark);
            border-radius: 0; /* Pas d'arrondis */
            padding: 1.8rem 2.2rem; /* Plus de padding pour l'aération */
            display: flex;
            align-items: center;
            border-bottom: 2px solid var(--accent-red-orange); /* Bordure accentuée forte */
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .custom-gradient-header h1,
        .custom-gradient-header h3,
        .custom-gradient-header h4,
        .custom-gradient-header h5 {
            color: inherit;
            margin-bottom: 0;
            font-weight: 700;
        }

        /* Classe pour le texte accentué (générique) */
        .accent-text {
            color: var(--accent-red-orange) !important;
        }

        /* --- Formulaires et éléments de saisie --- */
        .form-control::placeholder {
            color: var(--placeholder-color);
            opacity: 1;
        }
        .form-control, .form-select {
            border-radius: 0; /* Pas d'arrondis */
            border: 2px solid var(--border-subtle); /* Bordure plus subtile, aérée */
            padding: 0.9rem 1.2rem; /* Plus de padding */
            font-size: 1rem; /* Taille standard */
            color: var(--text-on-light);
            background-color: var(--light-pure-white);
            transition: border-color 0.1s ease, box-shadow 0.1s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--focus-ring-color); /* Maintien de l'accent au focus */
        }

        /* --- Boutons personnalisés --- */
        .custom-primary-button {
            background-color: var(--button-background); /* Couleur unie accentuée */
            border: none;
            color: var(--text-on-dark); /* Texte blanc sur bouton accentué */
            padding: 1rem 2.5rem; /* Plus de padding pour l'aération et la présence */
            border-radius: 0; /* Pas d'arrondis */
            font-size: 1.15rem; /* Plus grande taille */
            font-weight: 700; /* Gras */
            transition: all 0.1s ease-in-out;
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            text-transform: uppercase; /* Texte en capitales */
            letter-spacing: 0.05em;
        }

        .custom-primary-button i {
            color: var(--text-on-dark);
            margin-right: 0.7rem; /* Plus d'espace entre icône et texte */
        }

        .custom-primary-button:hover {
            background-color: var(--button-hover-background); /* Léger changement de couleur au survol */
            transform: none; /* Pas de transformation */
            box-shadow: var(--shadow-none);
            color: var(--text-on-dark);
        }

        .custom-primary-button:active {
            transform: none;
            box-shadow: var(--shadow-none);
        }

        /* Style pour le bouton d'envoi de message circulaire */
        .custom-send-button {
            background-color: var(--button-background);
            border: none;
            color: var(--text-on-dark);
            width: 65px; /* Plus grand */
            height: 65px;
            border-radius: 0; /* Carré */
            font-size: 2.2rem; /* Icône plus grande */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-none);
            transition: all 0.1s ease-in-out;
        }

        .custom-send-button i {
            color: var(--text-on-dark);
        }

        .custom-send-button:hover {
            background-color: var(--button-hover-background);
            transform: none;
            box-shadow: var(--shadow-none);
        }

        .custom-send-button:active {
            transform: none;
            box-shadow: var(--shadow-none);
        }

        /* Style pour les boutons secondaires Bootstrap */
        .btn-outline-secondary {
            border: 2px solid var(--secondary-dark); /* Bordure épaisse */
            color: var(--secondary-dark); /* Texte gris foncé */
            transition: all 0.1s ease-in-out;
            font-weight: 700;
            padding: 0.85rem 1.8rem; /* Plus de padding */
            border-radius: 0; /* Pas d'arrondis */
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .btn-outline-secondary:hover {
            background-color: var(--secondary-dark); /* Fond gris foncé au survol */
            color: var(--text-on-dark); /* Texte blanc */
            transform: none;
            box-shadow: var(--shadow-none);
        }

        .btn-outline-secondary:active {
            transform: none;
            box-shadow: var(--shadow-none);
        }

        /* --- Styles des messages de conversation (minimaliste, fonctionnel) --- */
        .message-area-container {
            background-color: var(--light-pure-white);
            border: 2px solid var(--border-subtle); /* Bordure plus subtile pour aérer */
            border-radius: 0;
            padding: 25px; /* Plus de padding pour l'aération */
            height: 550px;
            overflow-y: auto;
            box-shadow: var(--shadow-none);
        }

        .message-row {
            margin-bottom: 12px; /* Plus d'espace entre les messages */
        }

        .message-bubble {
            padding: 14px 20px; /* Plus de padding pour les bulles */
            border-radius: 0; /* Carré */
            font-size: 1rem; /* Taille standard pour la lisibilité */
            line-height: 1.6; /* Plus d'interlignage dans les bulles */
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            max-width: 60%; /* Encore plus étroit pour un aspect plus "bloc" */
            border: 1px solid var(--border-subtle); /* Bordure fine pour délimiter les bulles */
        }

        .message-sent {
            background-color: var(--chat-bg-my-message);
            border-left: 4px solid var(--accent-red-orange); /* Bordure accentuée pour distinguer */
        }

        .message-received {
            background-color: var(--chat-bg-other-message);
            border-right: 4px solid var(--secondary-dark); /* Bordure plus sobre pour distinguer */
        }

        .message-timestamp {
            font-size: 0.75rem; /* Plus lisible */
            color: var(--chat-timestamp-color);
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .message-bubble .fas {
            font-size: 0.8rem;
            margin-left: 6px;
        }

    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid"> {{-- Utilise container-fluid pour la navbar --}}
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-search"></i> {{ config('app.name', 'TrouveTout') }}
            </a>
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
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">MODIFIER PROFIL</a>
                                <a class="dropdown-item" href="{{ route('messages.index') }}">
                                    BOÎTE DE RÉCEPTION
                                    @if ($unreadCount > 0)
                                        <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                                    @endif
                                </a>
                                <a class="dropdown-item dropdown-item-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    DÉCONNEXION
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @else
                        {{-- Liens pour les utilisateurs non connectés --}}
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">CONNEXION</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">INSCRIPTION</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container mt-4"> {{-- Le contenu principal reste dans un container pour la lisibilité --}}
            @yield('content')
        </div>
    </main>

    {{-- Scripts JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @yield('scripts')
</body>
</html>
