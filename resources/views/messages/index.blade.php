@extends('layouts.app')

@section('title', 'Mes Conversations - TrouveTout')

@section('styles')
<style>
    /* Styles généraux pour les titres principaux */
    .custom-heading {
        font-family: var(--font-heading);
        font-weight: 700;
        color: var(--primary-color);
        font-size: 2.8rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
    }

    .custom-heading i {
        font-size: 2.5rem;
        margin-right: 0.75rem;
        color: var(--primary-color);
    }

    /* Styles pour les boutons généraux */
    .custom-primary-button {
        padding: 1rem 2.5rem;
        font-size: 1.15rem;
        font-weight: 700;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        transition: all 0.2s ease-in-out;
        background-color: var(--primary-color);
        color: var(--text-on-dark);
        border: none;
        box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3);
    }

    .custom-primary-button:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(var(--primary-rgb), 0.4);
    }

    .custom-primary-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(var(--primary-rgb), 0.2);
    }

    .custom-secondary-button {
        padding: 0.85rem 2rem; /* Légèrement plus petit que le primary pour la création */
        font-size: 1rem;
        font-weight: 700;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        transition: all 0.2s ease-in-out;
        background-color: var(--secondary-color);
        color: var(--text-on-dark);
        border: none;
        box-shadow: 0 4px 12px rgba(var(--secondary-rgb), 0.2);
    }

    .custom-secondary-button:hover {
        background-color: var(--secondary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(var(--secondary-rgb), 0.3);
    }

    .custom-secondary-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(var(--secondary-rgb), 0.15);
    }

    /* Styles pour les alertes (succès, erreur, info) */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
    }

    .alert .fas {
        font-size: 1.2rem;
        margin-top: 0.15rem;
    }

    .alert > div {
        flex-grow: 1;
        padding-left: 0.75rem;
    }

    .alert-heading {
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 1.5rem;
        color: inherit;
    }

    .alert-success {
        background-color: var(--success-light);
        color: var(--success-dark);
        border-color: var(--success-color);
        box-shadow: 0 2px 10px rgba(var(--success-rgb), 0.1);
    }

    .alert-success .fas {
        color: var(--success-dark);
    }

    .alert-danger {
        background-color: var(--danger-light);
        color: var(--danger-dark);
        border-color: var(--danger-color);
        box-shadow: 0 2px 10px rgba(var(--danger-rgb), 0.1);
    }

    .alert-danger .fas {
        color: var(--danger-dark);
    }

    .alert-info {
        background-color: var(--info-light);
        color: var(--info-dark);
        border-color: var(--info-color);
        box-shadow: 0 2px 10px rgba(var(--info-rgb), 0.1);
    }

    .alert-info .fas {
        color: var(--info-dark);
    }

    .alert .btn-close {
        font-size: 0.8rem;
        padding: 0;
        margin-left: 1rem;
        color: inherit;
        opacity: 0.7;
    }
    .alert .btn-close:hover {
        opacity: 1;
    }

    /* Styles spécifiques à cette page de conversations */
    .list-group {
        border-radius: 12px; /* Coins arrondis pour la liste des conversations */
        overflow: hidden; /* Assure que les bordures internes s'arrondissent */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08); /* Ombre cohérente */
    }

    .custom-conversation-item {
        border: none !important; /* Retire les bordures par défaut du list-group-item */
        border-bottom: 1px solid var(--border-subtle) !important; /* Ajoute une bordure subtile entre les éléments */
        background-color: var(--light-pure-white); /* Fond blanc pur */
        padding: 1.25rem 1.75rem; /* Padding généreux */
        transition: all 0.2s ease-in-out;
    }

    .custom-conversation-item:last-child {
        border-bottom: none !important; /* Pas de bordure pour le dernier élément */
    }

    .custom-conversation-item:hover {
        background-color: var(--background-hover) !important; /* Effet de survol */
        transform: translateY(-2px); /* Léger effet de soulèvement */
    }

    .custom-unread-conversation {
        background-color: var(--info-light) !important; /* Couleur de fond pour les conversations non lues (info-light) */
        border-left: 5px solid var(--primary-color) !important; /* Bordure primaire pour les non lus */
        padding-left: calc(1.75rem - 5px); /* Ajuste le padding pour compenser la bordure gauche */
    }

    .custom-avatar-sm {
        width: 55px; /* Taille légèrement plus grande pour l'avatar */
        height: 55px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid var(--border-subtle); /* Bordure subtile autour de l'avatar */
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* Styles pour le contenu de l'élément de conversation */
    .custom-conversation-item h5 {
        font-family: var(--font-body);
        font-weight: 600;
        font-size: 1.2rem;
    }

    .custom-conversation-item h5.text-primary { /* Pour le nom si non lu */
        color: var(--primary-dark) !important; /* Couleur plus foncée pour le texte principal */
    }

    .custom-conversation-item small.text-muted {
        font-size: 0.9em;
    }

    .custom-conversation-item p.text-truncate {
        font-size: 0.95rem;
        line-height: 1.4;
    }

    .custom-unread-badge {
        background-color: var(--danger-color) !important; /* Badge de non lu en rouge */
        color: var(--text-on-dark);
        border-radius: 1.5rem; /* Forme de pilule plus prononcée */
        padding: 0.5em 0.8em;
        font-weight: 700;
        font-size: 0.9em; /* Taille légèrement ajustée */
        min-width: 28px; /* Taille minimale pour les chiffres */
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="custom-heading mb-0"><i class="fas fa-comments me-3"></i> {{ __('Mes Conversations') }}</h1>
        <a href="{{ route('annonces.index') }}" class="btn custom-secondary-button">
            <i class="fas fa-search me-2"></i>{{ __('Parcourir les annonces') }}
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($conversations->isEmpty())
        <div class="alert alert-info text-center p-4 shadow-sm rounded">
            <h4 class="alert-heading mb-3"><i class="fas fa-info-circle me-2"></i> {{ __('Aucune conversation') }}</h4>
            <p class="lead mb-2">
                {{ __('Vous n\'avez aucune conversation pour le moment.') }}
            </p>
            <p class="mb-0">
                {{ __('Trouvez une annonce qui vous intéresse pour en initier une !') }}
            </p>
            <hr>
            <a href="{{ route('annonces.index') }}" class="btn custom-primary-button mt-3">
                <i class="fas fa-plus-circle me-2"></i> {{ __('Commencer une discussion') }}
            </a>
        </div>
    @else
        <div class="list-group">
            @foreach ($conversations as $conversation)
                @php
                    $currentUser = Auth::user();
                    $otherParticipant = $conversation->otherUser;
                    $annonce = $conversation->annonce;
                    $lastMessage = $conversation->lastMessage;

                    // Détermine si la conversation a des messages non lus pour l'utilisateur actuel
                    $hasUnread = $conversation->unreadCount > 0;
                    // Détermine si le dernier message vient de l'interlocuteur (et non lu par nous)
                    $isOtherUserLastMessage = $lastMessage->sender_id !== $currentUser->id;
                    $displayBoldLastMessage = $hasUnread && $isOtherUserLastMessage;

                    // URL de l'avatar par défaut si l'utilisateur n'en a pas.
                    $avatarUrl = $otherParticipant->profile_picture_url ?? asset('images/default_avatar.png');
                @endphp

                <a href="{{ route('messages.show', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $otherParticipant->id]) }}"
                   class="list-group-item list-group-item-action custom-conversation-item {{ $hasUnread ? 'custom-unread-conversation' : '' }}">
                    <div class="d-flex w-100 align-items-center">
                        <img src="{{ $avatarUrl }}" class="custom-avatar-sm me-3" alt="{{ $otherParticipant->name }}">

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="mb-0 {{ $hasUnread ? 'text-primary' : 'text-dark' }}">
                                    {{ $otherParticipant->name ?? 'Utilisateur inconnu' }}
                                </h5>
                                <small class="text-muted {{ $hasUnread ? 'fw-bold' : '' }}">
                                    <i class="far fa-clock me-1"></i> {{ $lastMessage->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <p class="mb-0 text-truncate {{ $hasUnread ? 'fw-bold text-dark' : 'text-secondary' }}" style="max-width: 90%;">
                                <small class="d-block text-muted mb-1">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ __('À propos de') }} : <span class="fw-semibold">{{ Str::limit($annonce->Titre, 35) }}</span>
                                </small>
                                <span class="{{ $displayBoldLastMessage ? 'fw-bold' : '' }}">
                                    @if ($lastMessage->sender_id === $currentUser->id)
                                        {{ __('Vous') }} :
                                    @else
                                        {{ Str::limit($otherParticipant->name ?? 'Quelqu\'un', 10) }} :
                                    @endif
                                </span>
                                {{ Str::limit($lastMessage->content, 60) }}
                            </p>
                        </div>

                        @if ($hasUnread)
                            <span class="badge custom-unread-badge ms-3">
                                {{ $conversation->unreadCount }}
                                <span class="visually-hidden">{{ __('messages non lus') }}</span>
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
