<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TrouveTout') }} - @yield('title', 'Accueil')</title>

    {{-- Liens CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Google Fonts : Inter pour le corps, Onest pour les titres (pour un style moderne) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">


    {{-- Styles CSS personnalisés globaux --}}
    <style>
        :root {
            /* Palette de couleurs Modernisée pour un style plus doux et aéré */
            --primary-dark: #2c3e50; /* Bleu-gris foncé, moins noir agressif, pour les textes et fonds principaux */
            --secondary-dark: #5a6a7c; /* Gris moyen-foncé, pour des éléments secondaires */
            --accent-red-orange: #ff6b6b; /* Un rouge-orange plus doux et chaleureux (corail) */
            --light-pure-white: #f8f9fa; /* Blanc cassé/gris très clair pour les fonds principaux, moins dur que le blanc pur */
            --text-on-light: #2c3e50; /* Texte foncé sur fonds clairs */
            --text-on-dark: #ecf0f1; /* Texte très clair sur fonds sombres */
            --border-strong: rgba(0, 0, 0, 0.08); /* Bordure très subtile, presque invisible, gris très très clair */
            --border-subtle: rgba(0, 0, 0, 0.04); /* Bordure encore plus subtile ou transparente */

            /* Couleurs unies modernisées */
            --header-background: #34495e; /* Un bleu-gris foncé pour la navbar */
            --button-background: var(--accent-red-orange);
            --button-hover-background: #e74c3c; /* Un corail légèrement plus foncé au survol */

            /* Couleurs de feedback */
            --success-color: #2ecc71; /* Vert doux */
            --danger-color: #e74c3c; /* Rouge plus doux */
            --warning-color: #f39c12; /* Jaune-orange */
            --info-color: #3498db; /* Bleu plus doux */

            /* Variables de lisibilité et UX */
            --focus-ring-color: rgba(255, 107, 107, 0.4); /* Basé sur le nouvel accent */
            --link-hover-color: var(--accent-red-orange);
            --placeholder-color: #95a5a6; /* Gris bleuté plus doux pour le placeholder */

            /* Ombres minimalistes ou absentes */
            --shadow-none: none;

            /* Polices Modernisées : Onest pour les titres, Inter pour le corps */
            --font-heading: 'Onest', sans-serif; /* Nouveau pour les titres */
            --font-body: 'Inter', sans-serif;    /* Reste Inter pour le corps */

            /* Variables pour la discussion / chat (ajustées au nouveau thème) */
            --chat-bg-my-message: #e0f2f7; /* Bleu très clair pour les messages envoyés */
            --chat-bg-other-message: var(--light-pure-white); /* Blanc cassé pour les messages reçus */
            --chat-text-color: var(--text-on-light);
            --chat-timestamp-color: #7f8c8d; /* Gris plus doux */
            --chat-read-icon-color: var(--success-color);
            --chat-unread-icon-color: #bdc3c7;
            --chat-border-color: rgba(0, 0, 0, 0.05); /* Bordure très légère pour le chat */
            --chat-background: var(--light-pure-white);

            --primary-color: var(--primary-dark);

            /* --- NOUVELLES VARIABLES AJOUTÉES POUR COHÉRENCE --- */
            --primary-light: #e0f2f7; /* Une version plus claire du primary-dark pour certains éléments comme les bulles */
            --background-hover: #e9ecef; /* Un gris très clair pour les fonds au survol */
            --text-on-light-muted: #7f8c8d; /* Un gris doux pour les textes secondaires/timestamps */
            --primary-rgb: 44, 62, 80; /* Valeurs RGB de --primary-dark (#2c3e50) pour les ombres */
            --info-light: #d1ecf1; /* Une version claire de info-color pour les alertes info */
            --info-dark: #0c5460; /* Une version foncée de info-color pour le texte des alertes info */
            --info-rgb: 52, 152, 219; /* Valeurs RGB de --info-color (#3498db) pour les ombres des alertes */
            --gradient-primary: linear-gradient(to right, var(--primary-dark), #34495e); /* Dégradé primaire si utilisé ailleurs */
            --primary-light-border: #ccd9e0; /* Une bordure subtile pour les bulles envoyées */
        }

        /* --- Généralités et accessibilité --- */
        html {
            overflow-x: hidden;
        }
        body {
            font-family: var(--font-body);
            color: var(--text-on-light);
            background-color: var(--light-pure-white); /* Fond principal blanc cassé */
            line-height: 1.8; /* Plus d'interlignage pour aérer */
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Adapter le container par défaut de Bootstrap pour être plus large sur les grands écrans */
        @media (min-width: 1400px) { /* Pour les très grands écrans (xxl de Bootstrap) */
            .container {
                max-width: 1300px; /* Légèrement moins large pour plus de marges latérales perçues */
                padding-left: 4rem; /* Marges latérales plus généreuses */
                padding-right: 4rem;
            }
        }
        @media (min-width: 1600px) { /* Pour des écrans encore plus larges */
            .container {
                max-width: 1500px; /* Plus d'espace sur les très grands écrans */
                padding-left: 6rem; /* Encore plus de marges latérales */
                padding-right: 6rem;
            }
        }


        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            color: var(--primary-dark);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 2.5rem; /* Plus d'espace sous les titres */
            text-transform: uppercase;
            letter-spacing: 0.03em; /* Espacement légèrement ajusté */
        }
        h1 { font-size: 3.8rem; } /* Plus grand pour plus d'impact */
        h2 { font-size: 3rem; }
        h3 { font-size: 2.5rem; }
        h4 { font-size: 2rem; }
        h5 { font-size: 1.6rem; }
        h6 { font-size: 1.3rem; }


        p {
            margin-bottom: 1.5rem; /* Plus d'espace sous les paragraphes */
            font-size: 1.1rem; /* Légèrement plus grand pour la lisibilité */
        }

        a {
            color: var(--primary-dark);
            text-decoration: none;
            transition: color 0.2s ease-in-out, text-decoration 0.2s ease-in-out;
        }

        a:hover {
            color: var(--link-hover-color);
            text-decoration: underline;
        }

        /* Amélioration du focus pour l'accessibilité */
        .btn:focus, .form-control:focus, .form-select:focus,
        a:focus:not(.btn) {
            outline: 2px solid var(--focus-ring-color);
            outline-offset: 3px; /* Plus de décalage pour la visibilité */
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            border-color: var(--focus-ring-color);
        }

        /* --- Navbar --- */
        .navbar {
            background-color: var(--header-background);
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            padding: 1.5rem 0; /* Plus de padding pour aérer */
            border-bottom: none; /* Supprime la bordure forte du bas */
            border-radius: 0;
        }

        .navbar .container-fluid {
            padding-left: 4rem; /* Marges latérales généreuses pour la navbar aussi */
            padding-right: 4rem;
        }
        @media (min-width: 1400px) {
            .navbar .container-fluid {
                max-width: 1300px; /* Aligner avec le container principal */
                margin-left: auto;
                margin-right: auto;
                padding-left: 0; /* Pas de padding interne si le max-width est déjà appliqué */
                padding-right: 0;
            }
        }
        @media (min-width: 1600px) {
            .navbar .container-fluid {
                max-width: 1500px; /* Aligner avec le container principal */
            }
        }


        .navbar-brand {
            color: var(--text-on-dark) !important;
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 2.5rem; /* Encore plus grand */
            display: flex;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.08em; /* Plus d'espacement pour le logo */
        }
        .navbar-brand i {
            margin-right: 1rem; /* Plus d'espace */
            color: var(--accent-red-orange);
            font-size: 2.2rem; /* Plus grand */
        }

        .navbar-nav .nav-link {
            color: var(--text-on-dark) !important;
            font-weight: 600; /* Moins gras par défaut, plus subtil */
            padding: 1rem 1.8rem; /* Plus de padding */
            border-radius: 4px; /* Un léger arrondi pour la modernité, mais pas trop */
            transition: all 0.2s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.02em; /* Moins d'espacement pour la lisibilité */
        }

        .navbar-nav .nav-link:hover {
            color: var(--accent-red-orange) !important;
            background-color: rgba(255, 255, 255, 0.1); /* Fond très léger au survol */
        }

        .navbar-nav .nav-link.active {
            color: var(--text-on-dark) !important;
            background-color: var(--accent-red-orange);
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            font-weight: 700;
        }

        .navbar-nav .dropdown-toggle {
            color: var(--accent-red-orange) !important;
            font-weight: 700;
        }

        .dropdown-menu {
            background-color: var(--primary-dark);
            border: none; /* Supprime la bordure forte */
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            border-radius: 4px; /* Légers arrondis */
            padding: 0.5rem 0; /* Padding interne pour aérer */
        }

        .dropdown-item {
            color: var(--text-on-dark) !important;
            padding: 1rem 2rem; /* Plus de padding pour les items */
            transition: background-color 0.2s ease, color 0.2s ease;
            text-transform: uppercase;
            font-size: 0.95rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08); /* Ligne de séparation encore plus subtile */
        }
        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Fond très léger au survol */
            color: var(--accent-red-orange) !important;
        }

        .dropdown-item-danger:hover {
            background-color: var(--danger-color) !important;
            color: var(--text-on-dark) !important;
        }

        /* Badge pour les messages non lus dans la navbar */
        .navbar .badge.bg-danger {
            background-color: var(--accent-red-orange) !important;
            color: var(--text-on-dark);
            font-size: 0.9rem; /* Plus grand */
            padding: 0.4em 0.7em;
            border-radius: 50px; /* Totalement rond pour une touche moderne */
            transform: translate(25%, -50%); /* Ajuster position */
            min-width: 30px;
            text-align: center;
        }

        /* --- Header de carte / éléments avec dégradé (maintenant flat) --- */
        .custom-gradient-header {
            background-color: var(--primary-dark);
            color: var(--text-on-dark);
            border-radius: 4px; /* Légers arrondis */
            padding: 2.5rem 3rem; /* Plus de padding pour aérer la section entière */
            display: flex;
            align-items: center;
            border-bottom: none; /* Supprime la bordure accentuée forte */
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 3rem; /* Double le padding entre les sections */
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
            border-radius: 4px; /* Légers arrondis */
            border: 1px solid var(--border-subtle); /* Bordure très fine et subtile */
            padding: 1rem 1.4rem; /* Plus de padding */
            font-size: 1rem;
            color: var(--text-on-light);
            background-color: var(--light-pure-white);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--focus-ring-color);
        }

        /* --- Boutons personnalisés --- */
        .custom-primary-button {
            background-color: var(--button-background);
            border: none;
            color: var(--text-on-dark);
            padding: 1.2rem 3rem; /* Plus de padding pour la présence et l'aération */
            border-radius: 4px; /* Légers arrondis */
            font-size: 1.2rem; /* Plus grande taille */
            font-weight: 700;
            transition: all 0.2s ease-in-out;
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .custom-primary-button i {
            color: var(--text-on-dark);
            margin-right: 0.8rem;
        }

        .custom-primary-button:hover {
            background-color: var(--button-hover-background);
            transform: translateY(-2px); /* Très léger effet au survol */
            box-shadow: var(--shadow-none); /* Pas d'ombre, même au survol */
            color: var(--text-on-dark);
        }

        .custom-primary-button:active {
            transform: translateY(0);
            box-shadow: var(--shadow-none);
        }

        /* Style pour le bouton d'envoi de message circulaire */
        .custom-send-button {
            background-color: var(--button-background);
            border: none;
            color: var(--text-on-dark);
            width: 70px; /* Plus grand */
            height: 70px;
            border-radius: 50%; /* Arrondi parfait */
            font-size: 2.5rem; /* Icône plus grande */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-none); /* Pas d'ombre */
            transition: all 0.2s ease-in-out;
        }

        .custom-send-button i {
            color: var(--text-on-dark);
        }

        .custom-send-button:hover {
            background-color: var(--button-hover-background);
            transform: translateY(-2px);
            box-shadow: var(--shadow-none); /* Pas d'ombre, même au survol */
        }

        .custom-send-button:active {
            transform: translateY(0);
            box-shadow: var(--shadow-none);
        }

        /* Style pour les boutons secondaires Bootstrap */
        .btn-outline-secondary {
            border: 1px solid var(--secondary-dark); /* Bordure très fine */
            color: var(--secondary-dark);
            transition: all 0.2s ease-in-out;
            font-weight: 600; /* Moins gras */
            padding: 0.9rem 2rem; /* Plus de padding */
            border-radius: 4px; /* Légers arrondis */
            text-transform: uppercase;
            letter-spacing: 0.03em;
            box-shadow: var(--shadow-none); /* Pas d'ombre par défaut */
        }

        .btn-outline-secondary:hover {
            background-color: var(--secondary-dark);
            color: var(--text-on-dark);
            transform: translateY(-1px); /* Léger effet au survol */
            box-shadow: var(--shadow-none); /* Pas d'ombre, même au survol */
        }

        .btn-outline-secondary:active {
            transform: translateY(0);
            box-shadow: var(--shadow-none);
        }

        /* --- Styles des messages de conversation (minimaliste, fonctionnel) --- */
        .message-area-container {
            background-color: var(--light-pure-white);
            border: 1px solid var(--chat-border-color); /* Bordure très fine */
            border-radius: 8px; /* Plus d'arrondis pour la douceur */
            padding: 30px; /* Plus de padding */
            height: 550px;
            overflow-y: auto;
            box-shadow: var(--shadow-none); /* Pas d'ombre */
        }

        .message-row {
            margin-bottom: 15px; /* Plus d'espace entre les messages */
        }

        .message-bubble {
            padding: 16px 22px; /* Plus de padding pour les bulles */
            border-radius: 12px; /* Arrondis plus généreux */
            font-size: 1.05rem;
            line-height: 1.7;
            box-shadow: var(--shadow-none); /* Pas d'ombre sur les bulles elles-mêmes */
            max-width: 65%; /* Légèrement plus large */
            border: none; /* Supprime la bordure des bulles */
        }

        .message-sent {
            background-color: var(--chat-bg-my-message);
            border-left: 5px solid var(--accent-red-orange); /* Bordure accentuée plus large */
        }

        .message-received {
            background-color: var(--chat-bg-other-message);
            border-right: 5px solid var(--secondary-dark); /* Bordure plus large */
        }

        .message-timestamp {
            font-size: 0.8rem;
            color: var(--chat-timestamp-color);
            margin-top: 8px;
            text-transform: none; /* Moins agressif */
            letter-spacing: 0;
            opacity: 0.8; /* Légèrement transparent */
        }

        .message-bubble .fas {
            font-size: 0.9rem;
            margin-left: 8px;
        }

        /* Ajustement de la marge principale de la main */
        main.py-4 {
            padding-top: 4rem !important; /* Double le padding pour aérer */
            padding-bottom: 4rem !important; /* Double le padding pour aérer */
        }
        .container.mt-4 {
            margin-top: 3rem !important; /* Plus de marge au-dessus du contenu */
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
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
                    {{-- Condition pour afficher le lien "Catégories" UNIQUEMENT pour les admins --}}
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">Catégories</a>
                            </li>
                        @endif
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('annonces.gestion') ? 'active' : '' }}" href="{{ route('annonces.gestion') }}">Mes Annonces</a>
                    </li>
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
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    {{-- Scripts JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @yield('scripts')
</body>
</html>
