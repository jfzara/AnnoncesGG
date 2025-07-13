@extends('layouts.app')

@section('title', 'Conversation avec ' . ($otherUser->name ?? 'Utilisateur inconnu'))

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Conversation avec <span class="text-primary">{{ $otherUser->name ?? 'Utilisateur inconnu' }}</span></h1>
        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
            <i class="fas fa-arrow-left me-2"></i> Retour aux conversations
        </a>
    </div>

    {{-- Informations sur l'annonce --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Annonce concernée : <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="text-white text-decoration-none fw-bold">{{ $annonce->Titre }}</a></h5>
        </div>
        <div class="card-body">
            <p class="card-text text-muted">{{ Str::limit($annonce->DescriptionAbregee, 150) }}</p>
            <p class="card-text mb-0"><small class="text-success fw-bold">Prix : {{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit / À discuter' }}</small></p>
        </div>
    </div>

    {{-- Messages de la conversation --}}
    <div id="message-area" class="message-area-container border rounded-3 p-3 mb-4 bg-white shadow-sm">
        @if ($messages->isEmpty())
            <div class="alert alert-info text-center">
                Aucun message dans cette conversation. Commencez la discussion !
            </div>
        @else
            {{-- Le wrapper Flexbox pour tous les messages --}}
            <div class="chat-messages-display">
                @foreach ($messages as $message)
                    @if ($message->sender_id === Auth::id())
                        {{-- Mes messages (envoyés) - alignés à gauche --}}
                        <div class="message-row my-message-row">
                            <div class="message-bubble message-sent @if($message->read_at_receiver) message-read @else message-unread @endif">
                                <p class="mb-0 message-content-text">{{ $message->content }}</p>
                                <small class="message-timestamp d-block text-end mt-1">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                    <i class="fas fa-check-double ms-1 @if($message->read_at_receiver) text-read-icon @else text-unread-icon @endif" title="{{ $message->read_at_receiver ? 'Lu par ' . ($otherUser->name ?? 'l\'utilisateur') : 'Envoyé' }}"></i>
                                </small>
                            </div>
                        </div>
                    @else
                        {{-- Messages de l'autre utilisateur (reçus) - alignés à droite --}}
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
    <div class="card p-3 shadow-lg border-0">
        <h5 class="mb-3 text-primary">Envoyer un message</h5>
        <form action="{{ route('messages.store', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $otherUser->id]) }}" method="POST">
            @csrf
            <div class="mb-3">
                <textarea name="content" id="content" class="form-control" rows="3" placeholder="Écrivez votre message ici..." required></textarea>
                @error('content')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center">
                <i class="fas fa-paper-plane me-2"></i> Envoyer le message
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var messageArea = document.getElementById('message-area');
        if (messageArea) {
            // Défiler vers le bas seulement s'il y a du contenu réel
            if (messageArea.scrollHeight > messageArea.clientHeight) {
                messageArea.scrollTop = messageArea.scrollHeight;
            }
        }
    });
</script>
@endsection

@section('styles')
<style>
    :root {
        --chat-bg-my-message: #DCF8C6; /* Vert très clair, comme WhatsApp */
        --chat-bg-other-message: #E5E5EA; /* Gris clair, comme iMessage */
        --chat-text-color: #212529;
        --chat-timestamp-color: #888;
        --chat-read-icon-color: #4CAF50;
        --chat-unread-icon-color: #AAA;
        --chat-border-color: #dee2e6;
        --chat-background: #f8f9fa;
    }

    .message-area-container {
        background-color: var(--chat-background);
        border: 1px solid var(--chat-border-color);
        border-radius: 0.375rem;
        padding: 15px;
        display: flex; /* Ceci reste flex pour contenir le chat-messages-display */
        flex-direction: column;
        height: 550px;
        overflow-y: auto;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    /* Nouveau wrapper pour tous les messages */
    .chat-messages-display {
        display: flex;
        flex-direction: column; /* Les messages s'empilent verticalement */
        width: 100%;
        flex-grow: 1;
    }

    .message-row {
        display: flex;
        width: 100%; /* Chaque ligne prend toute la largeur */
        margin-bottom: 8px; /* Espacement entre les messages */
    }

    .my-message-row {
        justify-content: flex-start; /* Mes messages à gauche */
    }

    .other-message-row {
        justify-content: flex-end; /* Messages de l'autre à droite */
    }

    .message-bubble {
        padding: 8px 12px;
        border-radius: 18px;
        font-size: 0.9rem;
        line-height: 1.4;
        word-wrap: break-word;
        white-space: pre-wrap; /* Maintient les sauts de ligne */
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        max-width: 75%; /* La bulle ne prendra pas toute la largeur */
        box-sizing: border-box; /* Pour inclure le padding dans la max-width */
    }

    .message-sent {
        background-color: var(--chat-bg-my-message);
        color: var(--chat-text-color);
        border-bottom-left-radius: 4px; /* Coin bas-gauche droit pour mes messages */
    }

    .message-received {
        background-color: var(--chat-bg-other-message);
        color: var(--chat-text-color);
        border-bottom-right-radius: 4px; /* Coin bas-droit droit pour les messages reçus */
    }

    .message-content-text {
        margin-bottom: 0;
    }

    .message-timestamp {
        font-size: 0.65rem;
        color: var(--chat-timestamp-color);
        opacity: 0.9;
        margin-top: 4px;
    }

    .message-bubble .fas {
        font-size: 0.6rem;
        margin-left: 5px;
    }

    .text-read-icon {
        color: var(--chat-read-icon-color);
    }

    .text-unread-icon {
        color: var(--chat-unread-icon-color);
    }
</style>
@endsection
