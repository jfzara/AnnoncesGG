@extends('layouts.app')

@section('title', $annonce->Titre ?? 'Détails de l\'Annonce - TrouveTout')

@section('styles')
<style>
    /* Styles spécifiques à la page de détails d'annonce */
    .custom-detail-img {
        max-height: 450px; /* Taille maximale pour les images de détails */
        width: auto; /* Ajustement automatique de la largeur */
        object-fit: contain; /* L'image s'adapte sans être coupée, mais peut laisser des bords vides */
        display: block; /* Centre l'image si elle est plus petite */
        margin: 0 auto 1.5rem auto; /* Centre et ajoute une marge en bas */
        border: 1px solid var(--border-subtle); /* Bordure très subtile */
        padding: 0.5rem; /* Petit padding intérieur */
        background-color: var(--light-pure-white); /* Fond blanc cassé pour l'image */
    }

    .card {
        border-radius: 8px; /* Plus d'arrondis pour la douceur sur les cartes */
        overflow: hidden; /* Assure que les coins de l'image sont aussi arrondis */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); /* Une ombre plus douce et perceptible */
        background-color: var(--light-pure-white); /* Fond blanc cassé */
    }

    .card-body p {
        font-size: 1.05rem; /* Légèrement plus grand pour le corps de texte */
        line-height: 1.7; /* Plus d'interlignage */
    }

    .card-body p.fw-bold {
        color: var(--primary-dark); /* Les titres de paragraphe sont en couleur principale */
        font-family: var(--font-body); /* Garder Inter pour les labels */
        font-weight: 700; /* Plus gras */
        font-size: 1.15rem; /* Taille légèrement augmentée */
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .card-body p.ms-4 { /* Pour les valeurs sous les labels */
        color: var(--text-on-light);
        font-size: 1.05rem;
        margin-bottom: 0.75rem !important; /* Moins d'espace pour ces p */
    }

    /* Icônes dans les paragraphes d'information */
    .card-body p i.fas {
        color: var(--accent-red-orange) !important; /* Utilise la couleur d'accent pour les icônes */
    }

    .card-body hr {
        border-top: 1px solid var(--border-strong); /* Lignes de séparation plus prononcées mais toujours subtiles */
        margin: 2.5rem 0; /* Plus de marges autour des séparateurs */
    }

    /* Style du badge de prix dans le header */
    .card-header .badge {
        background-color: var(--light-pure-white) !important; /* Blanc cassé pour le fond du badge */
        color: var(--accent-red-orange) !important; /* Rouge-orange pour le texte du prix */
        font-family: var(--font-heading); /* Police du titre pour le prix */
        font-weight: 900; /* Très gras pour le prix */
        font-size: 1.7rem; /* Plus grand pour le prix */
        padding: 0.7rem 1.5rem !important; /* Plus de padding pour le badge */
        border-radius: 50px !important; /* Totalement rond */
    }

    /* Style pour la section de description détaillée */
    .card-body .card.p-3.bg-light { /* Cible le bloc de description */
        background-color: var(--light-pure-white) !important; /* Utilise le blanc cassé pour le fond */
        border: 1px solid var(--border-subtle) !important; /* Bordure subtile */
        border-radius: 6px; /* Légers arrondis */
        box-shadow: none; /* Pas d'ombre */
        padding: 1.5rem !important; /* Padding interne */
    }

    .card-body .card.p-3.bg-light p {
        font-size: 1.05rem; /* Conserve la taille de base */
        color: var(--text-on-light); /* Couleur de texte par défaut */
    }

    /* Badges d'état (Expirée, Active) */
    .badge.bg-danger {
        background-color: var(--danger-color) !important;
        color: var(--text-on-dark) !important;
        font-size: 0.9rem;
        padding: 0.5em 0.8em;
        border-radius: 4px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .badge.bg-info {
        background-color: var(--info-color) !important; /* Utilisez votre variable info-color */
        color: var(--text-on-dark) !important; /* Le texte sur fond info sera clair */
        font-size: 0.9rem;
        padding: 0.5em 0.8em;
        border-radius: 4px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    /* Conteneur des boutons en bas */
    .d-flex.gap-2 .btn {
        padding: 1rem 2rem; /* Plus de padding pour les boutons */
        font-size: 1.1rem; /* Texte plus grand */
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-radius: 4px;
        box-shadow: none;
        transition: all 0.2s ease-in-out;
    }

    .d-flex.gap-2 .btn.btn-danger {
        background-color: var(--danger-color);
        border: none;
        color: var(--text-on-dark);
    }
    .d-flex.gap-2 .btn.btn-danger:hover {
        background-color: #c0392b; /* Un rouge un peu plus foncé au survol */
        transform: translateY(-2px);
    }
    .d-flex.gap-2 .btn.btn-danger:active {
        transform: translateY(0);
    }

    /* Style spécifique pour le bouton Contacter le vendeur */
    .btn.custom-primary-button {
        /* Les styles sont déjà définis dans app.blade.php, s'assurer qu'ils sont bien appliqués */
        background-color: var(--button-background);
        border: none;
        color: var(--text-on-dark);
        padding: 1.2rem 3rem; /* Utiliser le padding de votre custom-primary-button */
        border-radius: 4px;
        font-size: 1.2rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        transition: all 0.2s ease-in-out;
        box-shadow: none;
    }
    .btn.custom-primary-button:hover {
        background-color: var(--button-hover-background);
        transform: translateY(-2px);
    }
    .btn.custom-primary-button:active {
        transform: translateY(0);
    }

    /* Style pour le bouton Retour aux annonces */
    .btn.btn-outline-secondary {
        /* Utilisation de vos styles de btn-outline-secondary définis dans app.blade.php */
        border: 1px solid var(--secondary-dark);
        color: var(--secondary-dark);
        font-weight: 600;
        padding: 0.9rem 2rem;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        box-shadow: none;
        transition: all 0.2s ease-in-out;
    }
    .btn.btn-outline-secondary:hover {
        background-color: var(--secondary-dark);
        color: var(--text-on-dark);
        transform: translateY(-1px);
    }
    .btn.btn-outline-secondary:active {
        transform: translateY(0);
    }

    /* Style pour le bouton Modifier l'annonce (qui était custom-secondary-button dans index) */
    .btn.custom-secondary-button {
        background-color: var(--secondary-dark);
        border: none;
        color: var(--text-on-dark);
        padding: 1.2rem 3rem; /* Pour être cohérent avec les autres gros boutons */
        border-radius: 4px;
        font-size: 1.1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        transition: all 0.2s ease-in-out;
        box-shadow: none;
    }
    .btn.custom-secondary-button:hover {
        background-color: #4a596b;
        transform: translateY(-2px);
    }
    .btn.custom-secondary-button:active {
        transform: translateY(0);
    }

    /* Si l'utilisateur n'est pas authentifié, le bouton de contact aura une apparence spécifique */
    .btn.btn-outline-primary { /* Cette classe n'existe pas dans votre app.blade.php, il faut la définir ou la mapper */
        border: 1px solid var(--primary-dark);
        color: var(--primary-dark);
        background-color: transparent;
        font-weight: 700;
        padding: 1.2rem 3rem;
        border-radius: 4px;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        transition: all 0.2s ease-in-out;
        box-shadow: none;
    }
    .btn.btn-outline-primary:hover {
        background-color: var(--primary-dark);
        color: var(--text-on-dark);
        transform: translateY(-2px);
    }
    .btn.btn-outline-primary:active {
        transform: translateY(0);
    }

</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0"> {{-- Card plus grande ombre et pas de bordure --}}
                <div class="card-header custom-gradient-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">{{ $annonce->Titre ?? 'Annonce sans titre' }}</h3> {{-- Le texte blanc est hérité du custom-gradient-header --}}
                    <span class="badge">{{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit' }}</span>
                </div>
                <div class="card-body">
                    {{-- Section photo --}}
                    @if($annonce->Photo)
                        <div class="mb-4 text-center">
                            <img src="{{ asset('storage/' . $annonce->Photo) }}" class="img-fluid rounded custom-detail-img" alt="Photo de l'annonce">
                        </div>
                    @else
                        <div class="mb-4 text-center text-muted border p-4 rounded bg-light">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p class="mb-0">Aucune photo disponible pour cette annonce.</p>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="fw-bold mb-1"><i class="fas fa-tag me-2"></i> Catégorie :</p>
                            <p class="ms-4 mb-0">{{ $annonce->categorie->Description ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="fw-bold mb-1"><i class="fas fa-user-circle me-2"></i> Publié par :</p>
                            <p class="ms-4 mb-0">{{ $annonce->user->name ?? 'Utilisateur inconnu' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <p class="fw-bold mb-2"><i class="fas fa-align-left me-2"></i> Description Complète :</p>
                        <div class="card p-3 bg-light border-0"> {{-- Utilise les styles de carte pour la description --}}
                            <p class="mb-0">{{ $annonce->DescriptionComplete ?? $annonce->DescriptionAbregee ?? 'Pas de description détaillée fournie.' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="fw-bold mb-1"><i class="fas fa-calendar-alt me-2"></i> Date de parution :</p>
                            <p class="ms-4 mb-0">{{ $annonce->Parution->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($annonce->MiseAJour)
                                <p class="fw-bold mb-1"><i class="fas fa-history me-2"></i> Dernière mise à jour :</p>
                                <p class="ms-4 mb-0">{{ $annonce->MiseAJour->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="fw-bold mb-1"><i class="fas fa-calendar-times me-2"></i> Date d'expiration :</p>
                        <p class="ms-4 mb-0">
                            @if($annonce->DateFin)
                                {{ $annonce->DateFin->format('d/m/Y') }}
                                @if($annonce->DateFin->isPast())
                                    <span class="badge bg-danger ms-2"><i class="fas fa-exclamation-triangle me-1"></i> Expirée</span>
                                @else
                                    <span class="badge bg-info ms-2"><i class="fas fa-clock me-1"></i> Active</span>
                                @endif
                            @else
                                Aucune (active indéfiniment)
                            @endif
                        </p>
                    </div>

                    <div class="mt-4 border-top pt-3 d-flex justify-content-between align-items-center">
                        <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Retour aux annonces
                        </a>

                        @auth
                            @if(Auth::id() == $annonce->NoUtilisateur)
                                {{-- Boutons d'édition et suppression pour l'auteur de l'annonce --}}
                                <div class="d-flex gap-2">
                                    <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn custom-secondary-button">
                                        <i class="fas fa-edit me-2"></i> Modifier l'annonce
                                    </a>
                                    <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                            <i class="fas fa-trash-alt me-2"></i> Supprimer l'annonce
                                        </button>
                                    </form>
                                </div>
                            @else
                                {{-- Bouton pour contacter le vendeur si ce n'est pas sa propre annonce --}}
                                <a href="{{ route('messages.show', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $annonce->user->id]) }}"
                                   class="btn custom-primary-button" title="Contacter le vendeur">
                                    <i class="fas fa-envelope me-2"></i> Contacter le vendeur
                                </a>
                            @endif
                        @else
                            {{-- Si l'utilisateur n'est pas authentifié --}}
                            <a href="{{ route('login') }}" class="btn btn-outline-primary" title="Connectez-vous pour contacter">
                                <i class="fas fa-sign-in-alt me-2"></i> Connectez-vous pour contacter
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
