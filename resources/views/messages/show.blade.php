@extends('layouts.app')

@section('title', 'Conversation avec ' . ($otherUser->name ?? 'Utilisateur inconnu'))

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Discussion avec <span class="text-primary">{{ $otherUser->name ?? 'quelqu\'un' }}</span></h1>
        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary d-flex align-items-center" aria-label="Retour aux messages">
            <i class="fas fa-arrow-left me-2"></i> Messages
        </a>
    </div>

    {{-- Informations sur l'annonce --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header custom-gradient-header text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="announcement-title-yellow text-decoration-none fw-bold">{{ $annonce->Titre }}</a></h5>
        </div>
        <div class="card-body">
            <p class="card-text text-muted">{{ Str::limit($annonce->DescriptionAbregee, 150) }}</p>
            <p class="card-text mb-0"><small class="text-success fw-bold">Prix : {{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit / Offre' }}</small></p>
        </div>
    </div>

    {{-- Messages de la conversation --}}
    <div id="message-area" class="message-area-container border rounded-3 p-3 mb-4 bg-white shadow-sm">
        @if ($messages->isEmpty())
            <div class="alert alert-info text-center">
                Pas encore de messages. Lancez la conversation !
            </div>
        @else
            <div class="chat-messages-display">
                @foreach ($messages as $message)
                    @if ($message->sender_id === Auth::id())
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
        <h5 class="mb-3 text-primary">Votre message</h5>
        <form action="{{ route('messages.store', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $otherUser->id]) }}" method="POST">
            @csrf
            <div class="d-flex align-items-end mb-3">
                <textarea name="content" id="content" class="form-control me-2" rows="2" placeholder="Écrire un message..." required></textarea>
                <button type="submit" class="btn custom-send-button flex-shrink-0" aria-label="Envoyer">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            @error('content')
                <div class="text-danger small mt-1">Ce champ est requis.</div>
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
        --chat-bg-my-message: #DCF8C6;
        --chat-bg-other-message: #E5E5EA;
        --chat-text-color: #212529;
        --chat-timestamp-color: #888;
        --chat-read-icon-color: #4CAF50;
        --chat-unread-icon-color: #AAA;
        --chat-border-color: #dee2e6;
        --chat-background: #f8f9fa;
        --yellow-accent-color: #FFDA2F;
        --send-button-gradient: linear-gradient(to right, #0081b1 0%, #0419693d 100%);
        --placeholder-color: #AAAAAA;
    }

    .form-control::placeholder {
        color: var(--placeholder-color);
        opacity: 1;
    }
    .form-control::-webkit-input-placeholder {
        color: var(--placeholder-color);
    }
    .form-control:-ms-input-placeholder {
        color: var(--placeholder-color);
    }
    .form-control::-ms-input-placeholder {
        color: var(--placeholder-color);
    }

    .announcement-title-yellow {
        color: var(--yellow-accent-color) !important;
    }

    .custom-gradient-header {
        background: var(--send-button-gradient);
        border-top-left-radius: calc(0.375rem - 1px);
        border-top-right-radius: calc(0.375rem - 1px);
        padding: 1rem 1.25rem;
    }

    .custom-send-button {
        background: var(--send-button-gradient);
        border: none;
        color: white;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        font-size: 2.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, color 0.2s ease-in-out;
    }

    .custom-send-button i {
        color: white; /* Couleur initiale de l'icône */
        transition: color 0.2s ease-in-out; /* Transition pour la couleur de l'icône */
    }

    .custom-send-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        background-position: right center;
    }

    .custom-send-button:hover i {
        color: var(--yellow-accent-color) !important; /* Icône jaune au survol */
    }

    .custom-send-button:active {
        transform: translateY(0) scale(0.95);
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
    }

    .message-area-container {
        background-color: var(--chat-background);
        border: 1px solid var(--chat-border-color);
        border-radius: 0.375rem;
        padding: 15px;
        display: flex;
        flex-direction: column;
        height: 550px;
        overflow-y: auto;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .chat-messages-display {
        display: flex;
        flex-direction: column;
        width: 100%;
        flex-grow: 1;
    }

    .message-row {
        display: flex;
        width: 100%;
        margin-bottom: 8px;
    }

    .my-message-row {
        justify-content: flex-start;
    }

    .other-message-row {
        justify-content: flex-end;
    }

    .message-bubble {
        padding: 8px 12px;
        border-radius: 18px;
        font-size: 0.9rem;
        line-height: 1.4;
        word-wrap: break-word;
        white-space: pre-wrap;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        max-width: 75%;
        box-sizing: border-box;
    }

    .message-sent {
        background-color: var(--chat-bg-my-message);
        color: var(--chat-text-color);
        border-bottom-left-radius: 4px;
    }

    .message-received {
        background-color: var(--chat-bg-other-message);
        color: var(--chat-text-color);
        border-bottom-right-radius: 4px;
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
