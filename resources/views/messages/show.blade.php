@extends('layouts.app')

@section('title', 'Conversation avec ' . ($otherUser->name ?? 'Utilisateur inconnu'))

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">DISCUSSION AVEC <span class="accent-text">{{ $otherUser->name ?? 'QUELQU\'UN' }}</span></h1>
        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary d-flex align-items-center" aria-label="Retour aux messages">
            <i class="fas fa-arrow-left me-2"></i> MESSAGES
        </a>
    </div>

    {{-- Informations sur l'annonce --}}
    <div class="card mb-4 border-0">
        <div class="card-header custom-gradient-header text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="text-on-dark text-decoration-none fw-bold" style="color: var(--text-on-dark);">{{ $annonce->Titre }}</a>
            </h5>
        </div>
        <div class="card-body">
            <p class="card-text text-muted">{{ Str::limit($annonce->DescriptionAbregee, 150) }}</p>
            <p class="card-text mb-0"><small class="accent-text fw-bold">PRIX : {{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'GRATUIT / OFFRE' }}</small></p>
        </div>
    </div>

    {{-- Messages de la conversation --}}
    <div id="message-area" class="message-area-container mb-4">
        @if ($messages->isEmpty())
            <div class="alert alert-info text-center">
                PAS ENCORE DE MESSAGES. LANCEZ LA CONVERSATION !
            </div>
        @else
            <div class="chat-messages-display">
                @foreach ($messages as $message)
                    @if ($message->sender_id === Auth::id())
                        {{-- Message ENVOYÉ (par l'utilisateur actuel) --}}
                        <div class="message-row my-message-row">
                            <div class="message-bubble message-sent @if($message->read_at_receiver) message-read @else message-unread @endif">
                                <p class="mb-0 message-content-text">{{ $message->content }}</p>
                                <small class="message-timestamp d-block text-end mt-1">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                    <i class="fas fa-check-double ms-1 @if($message->read_at_receiver) text-read-icon @else text-unread-icon @endif" title="{{ $message->read_at_receiver ? 'Vu par ' . ($otherUser->name ?? 'l\'utilisateur') : 'Envoyé' }}"></i>
                                </small>
                            </div>
                        </div>
                    @else
                        {{-- Message REÇU (par l'autre utilisateur) --}}
                        <div class="message-row other-message-row">
                            <div class="message-bubble message-received @if($message->read_at_receiver) message-read @else message-unread @endif">
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
    <div class="card p-4 border-0">
        <h5 class="mb-3 accent-text">VOTRE MESSAGE</h5>
        <form action="{{ route('messages.store', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $otherUser->id]) }}" method="POST">
            @csrf
            <div class="d-flex align-items-end mb-3">
                <textarea name="content" id="content" class="form-control me-2" rows="3" placeholder="Écrire un message..." required></textarea>
                <button type="submit" class="btn custom-send-button flex-shrink-0" aria-label="Envoyer">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            @error('content')
                <div class="text-danger small mt-1">CE CHAMP EST REQUIS.</div>
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
            if (messageArea.children[0] && messageArea.children[0].classList.contains('chat-messages-display')) {
                messageArea.scrollTop = messageArea.scrollHeight;
            }
        }
    });
</script>
@endsection

@section('styles')
<style>
    .card {
        border: 2px solid var(--border-subtle) !important;
        border-radius: 0 !important;
        box-shadow: var(--shadow-none) !important;
    }

    .card-header.custom-gradient-header {
        background: var(--primary-dark) !important;
        border-radius: 0 !important;
        border-bottom: 2px solid var(--accent-red-orange) !important;
    }

    .custom-send-button {
        background-color: var(--button-background) !important;
        border-radius: 0 !important;
        width: 65px !important;
        height: 65px !important;
        box-shadow: var(--shadow-none) !important;
    }
    .custom-send-button:hover {
        background-color: var(--button-hover-background) !important;
        transform: none !important;
        box-shadow: var(--shadow-none) !important;
    }
    .custom-send-button:hover i {
        color: var(--text-on-dark) !important;
    }
    .custom-send-button i {
        color: var(--text-on-dark) !important;
    }

    .message-area-container {
        border: 2px solid var(--border-subtle) !important;
        background-color: var(--chat-background) !important;
        border-radius: 0 !important;
        padding: 25px !important;
        box-shadow: var(--shadow-none) !important;
        display: flex; /* Rend le conteneur flex pour que chat-messages-display prenne l'espace */
        flex-direction: column; /* Organise le contenu en colonne */
    }

    /* ************************************************************ */
    /* NOUVEAU : Correction de l'alignement des messages */
    /* ************************************************************ */

    .chat-messages-display {
        /* Plus besoin de flex-direction: column ici si chaque row est un flex */
        /* Pour un vrai zigzag, le parent doit juste contenir les lignes, et chaque ligne gère son alignement */
        /* Nous allons nous assurer que les message-row sont bien des display flex */
        flex-grow: 1; /* Permet à la zone de messages de prendre l'espace disponible */
        display: block; /* Ou un autre display si nécessaire, mais bloquant est simple */
        overflow-y: auto; /* Garde le défilement si le contenu dépasse */
    }

    .message-row {
        display: flex !important; /* Rendre chaque ligne de message un conteneur flex */
        width: 100% !important; /* Chaque ligne occupe toute la largeur */
        margin-bottom: 12px !important; /* Plus d'espace entre les messages */
    }

    .my-message-row {
        justify-content: flex-end !important; /* Mes messages (envoyés) sont à DROITE */
    }

    .other-message-row {
        justify-content: flex-start !important; /* Messages des autres (reçus) sont à GAUCHE */
    }

    .message-bubble {
        border-radius: 0 !important;
        padding: 14px 20px !important;
        line-height: 1.6 !important;
        max-width: 60% !important;
        border: 1px solid var(--border-subtle) !important;
        box-shadow: var(--shadow-none) !important;
    }

    .message-sent {
        background-color: var(--chat-bg-my-message) !important;
        color: var(--chat-text-color) !important;
        border-left: none !important;
        border-right: 4px solid var(--accent-red-orange) !important; /* Bordure accentuée à droite */
    }

    .message-received {
        background-color: var(--chat-bg-other-message) !important;
        color: var(--chat-text-color) !important;
        border-right: none !important;
        border-left: 4px solid var(--secondary-dark) !important; /* Bordure plus sobre à gauche */
    }
    /* ************************************************************ */


    .message-timestamp {
        font-size: 0.75rem !important;
        color: var(--chat-timestamp-color) !important;
        text-transform: uppercase !important;
        letter-spacing: 0.02em !important;
    }

    .text-read-icon {
        color: var(--success-color) !important;
    }

    .text-unread-icon {
        color: var(--chat-unread-icon-color) !important;
    }

    .announcement-title-link {
        color: var(--text-on-dark) !important;
    }
    .announcement-title-link:hover {
        color: var(--accent-red-orange) !important;
        text-decoration: underline !important;
    }

    textarea.form-control::placeholder {
        color: var(--placeholder-color) !important;
    }
</style>
@endsection
