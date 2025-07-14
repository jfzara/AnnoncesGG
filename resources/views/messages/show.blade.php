@extends('layouts.app')

@section('title', 'Conversation avec ' . ($otherUser->name ?? 'Utilisateur inconnu') . ' - TrouveTout')

@section('styles')
<style>
    /* Variables de couleurs spécifiques aux bulles de chat (à ajouter si non déjà dans _variables.scss) */
    :root {
        --chat-background: var(--light-pure-white); /* Fond de la zone de chat */
        --chat-bg-my-message: var(--primary-light); /* Fond des bulles envoyées */
        --chat-bg-other-message: var(--background-hover); /* Fond des bulles reçues */
        --chat-text-color: var(--text-on-light); /* Couleur du texte dans les bulles */
        --chat-timestamp-color: var(--text-on-light-muted); /* Couleur de l'horodatage */
        --chat-unread-icon-color: var(--text-on-light-muted); /* Couleur de l'icône "non lu" */
        --placeholder-color: var(--text-on-light-muted); /* Couleur du placeholder textarea */
    }

    /* Styles généraux pour le titre de la page */
    .custom-page-title {
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 2.5rem; /* Taille légèrement plus petite que les titres de section */
        color: var(--primary-color);
        margin-bottom: 1.5rem;
    }

    .custom-page-title .accent-text {
        color: var(--secondary-color); /* Couleur accent pour le nom de l'interlocuteur */
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* Bouton de retour */
    .custom-btn-back {
        background-color: var(--background-light);
        color: var(--text-on-light);
        border: 1px solid var(--border-subtle);
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .custom-btn-back:hover {
        background-color: var(--background-hover);
        color: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .custom-btn-back i {
        font-size: 1rem;
        margin-right: 0.5rem;
    }

    /* Carte d'information sur l'annonce */
    .annonce-info-card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: none; /* Supprime la bordure par défaut */
    }

    .annonce-info-card .card-header {
        background: var(--gradient-primary); /* Utilise le dégradé primaire */
        padding: 1.25rem 1.5rem;
        border-bottom: none;
        color: var(--text-on-dark);
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 1.15rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .annonce-info-card .card-header a {
        color: var(--text-on-dark);
        text-decoration: none;
        transition: color 0.2s ease-in-out;
    }

    .annonce-info-card .card-header a:hover {
        color: var(--secondary-light); /* Une couleur d'accent au survol */
        text-decoration: underline;
    }

    .annonce-info-card .card-body {
        padding: 1.5rem;
        background-color: var(--light-pure-white);
        color: var(--text-on-light);
    }

    .annonce-info-card .card-text.text-muted {
        color: var(--text-on-light-muted) !important;
        font-size: 0.95rem;
    }

    .annonce-info-card .accent-text.fw-bold {
        color: var(--primary-dark) !important; /* Couleur plus foncée pour le prix */
        font-size: 1.05rem;
    }

    /* Zone des messages */
    .message-area-container {
        border: 1px solid var(--border-subtle);
        background-color: var(--chat-background);
        border-radius: 12px; /* Coins arrondis pour la zone de chat */
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        min-height: 400px; /* Hauteur minimale pour la zone de chat */
        max-height: 600px; /* Hauteur maximale avec défilement */
        overflow-y: auto; /* Permet le défilement si les messages dépassent */
    }

    .chat-messages-display {
        flex-grow: 1; /* Permet à la zone de messages de prendre tout l'espace disponible */
        display: flex;
        flex-direction: column; /* Pour empiler les bulles verticalement */
        gap: 10px; /* Espace entre les messages */
    }

    .message-row {
        display: flex; /* Permet d'aligner les bulles à gauche ou à droite */
        width: 100%;
    }

    .my-message-row {
        justify-content: flex-end; /* Mes messages (envoyés) sont à DROITE */
    }

    .other-message-row {
        justify-content: flex-start; /* Messages des autres (reçus) sont à GAUCHE */
    }

    .message-bubble {
        border-radius: 18px; /* Rayon plus grand pour un look "bulle" */
        padding: 12px 18px;
        line-height: 1.5;
        max-width: 75%; /* Largeur maximale de la bulle */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); /* Légère ombre sur les bulles */
        position: relative; /* Pour le petit coin */
    }

    /* Petit coin des bulles */
    .message-bubble::before {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border: 10px solid transparent;
        pointer-events: none;
    }

    .message-sent {
        background-color: var(--chat-bg-my-message);
        color: var(--chat-text-color);
        /* Coins arrondis spécifiques pour la bulle envoyée */
        border-top-left-radius: 18px;
        border-bottom-left-radius: 18px;
        border-top-right-radius: 4px; /* Coin supérieur droit plus petit */
        border-bottom-right-radius: 18px;
        border: 1px solid var(--primary-light-border); /* Bordure subtile */
    }

    .message-sent::before {
        border-left-color: var(--chat-bg-my-message);
        right: -8px; /* Positionne le coin à droite */
        top: 0px; /* Positionne le coin en haut */
        transform: rotate(45deg);
    }

    .message-received {
        background-color: var(--chat-bg-other-message);
        color: var(--chat-text-color);
        /* Coins arrondis spécifiques pour la bulle reçue */
        border-top-left-radius: 4px; /* Coin supérieur gauche plus petit */
        border-bottom-left-radius: 18px;
        border-top-right-radius: 18px;
        border-bottom-right-radius: 18px;
        border: 1px solid var(--border-subtle); /* Bordure subtile */
    }

    .message-received::before {
        border-right-color: var(--chat-bg-other-message);
        left: -8px; /* Positionne le coin à gauche */
        top: 0px; /* Positionne le coin en haut */
        transform: rotate(-45deg);
    }

    .message-content-text {
        font-size: 1rem;
        word-wrap: break-word; /* Assure que le texte long ne déborde pas */
    }

    .message-timestamp {
        font-size: 0.75rem;
        color: var(--chat-timestamp-color);
        margin-top: 5px; /* Espace au-dessus de l'horodatage */
    }

    .text-read-icon {
        color: var(--success-color); /* Icône de lecture en vert */
    }

    .text-unread-icon {
        color: var(--chat-unread-icon-color); /* Icône "envoyé" en gris léger */
    }

    /* Formulaire de réponse */
    .reply-form-card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        background-color: var(--light-pure-white);
        border: none;
        padding: 2rem;
    }

    .reply-form-card .accent-text {
        font-family: var(--font-heading);
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1.4rem;
        margin-bottom: 1.5rem;
    }

    .custom-textarea {
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
        border: 1px solid var(--border-subtle);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease-in-out;
    }

    .custom-textarea:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.2);
        background-color: var(--light-pure-white);
    }

    .custom-textarea::placeholder {
        color: var(--placeholder-color);
        opacity: 0.8;
    }

    .custom-send-button {
        background-color: var(--primary-color);
        color: var(--text-on-dark);
        border: none;
        border-radius: 8px; /* Bouton arrondi */
        width: 55px; /* Taille fixe pour l'icône */
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem; /* Taille de l'icône */
        transition: all 0.2s ease-in-out;
        box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3);
    }

    .custom-send-button:hover {
        background-color: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(var(--primary-rgb), 0.4);
    }

    .custom-send-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(var(--primary-rgb), 0.2);
    }

    .error-message {
        color: var(--danger-color);
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    /* Alertes (réutiliser celles définies précédemment) */
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

    .alert-info {
        background-color: var(--info-light);
        color: var(--info-dark);
        border-color: var(--info-color);
        box-shadow: 0 2px 10px rgba(var(--info-rgb), 0.1);
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

</style>
@endsection

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="custom-page-title mb-0">
            DISCUSSION AVEC <span class="accent-text">{{ $otherUser->name ?? 'QUELQU\'UN' }}</span>
        </h1>
        <a href="{{ route('messages.index') }}" class="btn custom-btn-back" aria-label="Retour aux messages">
            <i class="fas fa-arrow-left"></i> {{ __('MESSAGES') }}
        </a>
    </div>

    {{-- Informations sur l'annonce --}}
    <div class="card annonce-info-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="text-decoration-none">
                    <i class="fas fa-tag me-2"></i> {{ $annonce->Titre }}
                </a>
            </h5>
        </div>
        <div class="card-body">
            <p class="card-text text-muted">{{ Str::limit($annonce->DescriptionAbregee, 150) }}</p>
            <p class="card-text mb-0">
                <small class="accent-text fw-bold">
                    PRIX : {{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'GRATUIT / OFFRE' }}
                </small>
            </p>
        </div>
    </div>

    {{-- Messages de la conversation --}}
    <div id="message-area" class="message-area-container mb-4">
        @if ($messages->isEmpty())
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> {{ __('PAS ENCORE DE MESSAGES. LANCEZ LA CONVERSATION !') }}
            </div>
        @else
            <div class="chat-messages-display">
                @foreach ($messages as $message)
                    @if ($message->sender_id === Auth::id())
                        {{-- Message ENVOYÉ (par l'utilisateur actuel) --}}
                        <div class="message-row my-message-row">
                            <div class="message-bubble message-sent">
                                <p class="mb-0 message-content-text">{{ $message->content }}</p>
                                <small class="message-timestamp d-block text-end mt-1">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                    <i class="fas fa-check-double ms-1 @if($message->read_at_receiver) text-read-icon @else text-unread-icon @endif"
                                       title="{{ $message->read_at_receiver ? 'Vu par ' . ($otherUser->name ?? 'l\'utilisateur') : 'Envoyé' }}"></i>
                                </small>
                            </div>
                        </div>
                    @else
                        {{-- Message REÇU (par l'autre utilisateur) --}}
                        <div class="message-row other-message-row">
                            <div class="message-bubble message-received">
                                <p class="mb-0 message-content-text">{{ $message->content }}</p>
                                <small class="message-timestamp d-block text-end mt-1">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- Formulaire de réponse --}}
    <div class="card reply-form-card p-4">
        <h5 class="mb-3 accent-text">{{ __('VOTRE MESSAGE') }}</h5>
        <form action="{{ route('messages.store', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $otherUser->id]) }}" method="POST">
            @csrf
            <div class="d-flex align-items-end mb-3">
                <textarea name="content" id="content" class="form-control custom-textarea me-2 @error('content') is-invalid @enderror"
                          rows="3" placeholder="Écrire un message..." required>{{ old('content') }}</textarea>
                <button type="submit" class="btn custom-send-button flex-shrink-0" aria-label="Envoyer">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            @error('content')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var messageArea = document.getElementById('message-area');
        if (messageArea) {
            // Fait défiler jusqu'en bas pour voir le dernier message
            messageArea.scrollTop = messageArea.scrollHeight;
        }
    });
</script>
@endsection
