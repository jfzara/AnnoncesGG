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
            <div class="chat-columns-wrapper d-flex flex-grow-1">
                {{-- Colonne des messages reçus (à gauche) --}}
                <div class="messages-received-column me-2">
                    @foreach ($messages as $message)
                        @if ($message->sender_id !== Auth::id())
                            <div class="message-bubble message-received @if($message->read_at_receiver) message-read @else message-unread @endif">
                                <p class="mb-0 message-content-text">{{ $message->content }}</p>
                                <small class="message-timestamp d-block text-end mt-1">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Colonne des messages envoyés (à droite) --}}
                <div class="messages-sent-column ms-2">
                    @foreach ($messages as $message)
                        @if ($message->sender_id === Auth::id())
                            <div class="message-bubble message-sent @if($message->read_at_receiver) message-read @else message-unread @endif">
                                <p class="mb-0 message-content-text">{{ $message->content }}</p>
                                <small class="message-timestamp d-block text-end mt-1">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                    <i class="fas fa-check-double ms-1 @if($message->read_at_receiver) text-read-icon @else text-unread-icon @endif" title="{{ $message->read_at_receiver ? 'Lu par ' . ($otherUser->name ?? 'l\'utilisateur') : 'Envoyé' }}"></i>
                                </small>
                            </div>
                        @endif
                    @endforeach
                </div>
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
        console.log("--- Débogage de la Vue des Messages ---");

        // 1. Log de l'ID de l'utilisateur connecté
        const authUserId = {{ Auth::id() }};
        console.log('ID de l\'utilisateur connecté (Auth::id()):', authUserId);

        // 2. Log des IDs des messages dans la collection
        const messages = @json($messages); // Convertit la collection Laravel en tableau JS
        console.log('Collection des messages passée à la vue:', messages);

        let receivedMessagesCount = 0;
        let sentMessagesCount = 0;

        messages.forEach(message => {
            console.log(`Message ID: ${message.id}, Sender ID: ${message.sender_id}, Receiver ID: ${message.receiver_id}, Content (début): ${message.content.substring(0, 30)}...`);

            if (message.sender_id !== authUserId) {
                receivedMessagesCount++;
                console.log(`  -> Ceci est un message REÇU (sender_id: ${message.sender_id} != Auth::id(): ${authUserId})`);
            } else {
                sentMessagesCount++;
                console.log(`  -> Ceci est un message ENVOYÉ (sender_id: ${message.sender_id} == Auth::id(): ${authUserId})`);
            }
        });

        console.log(`Nombre total de messages dans la collection: ${messages.length}`);
        console.log(`Messages comptés comme REÇUS: ${receivedMessagesCount}`);
        console.log(`Messages comptés comme ENVOYÉS: ${sentMessagesCount}`);

        console.log("--- Fin du Débogage de la Vue des Messages ---");

        // Script de défilement existant
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
        --chat-bg-sent: #E0FFD0;
        --chat-text-color: #212529;
        --chat-bg-received: #FFFFFF;
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
        display: flex;
        flex-direction: column;
        height: 550px;
        overflow-y: auto;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .chat-columns-wrapper {
        display: flex;
        flex-direction: row;
        width: 100%;
        flex-grow: 1;
        align-items: flex-start;
        gap: 15px;
    }

    .messages-received-column,
    .messages-sent-column {
        display: flex;
        flex-direction: column;
        flex-basis: 50%;
        max-width: 50%;
        padding-right: 5px;
        padding-left: 5px;
        height: auto;
        box-sizing: border-box;
    }

    /* Bordures de débogage */
    .messages-received-column {
        border: 3px solid #ff0000; /* Rouge vif pour la colonne des messages reçus */
    }

    .messages-sent-column {
        align-items: flex-end;
        border: 3px solid #0000ff; /* Bleu vif pour la colonne des messages envoyés */
    }
    /* Fin des bordures de débogage */

    .message-bubble {
        padding: 8px 12px;
        border-radius: 18px;
        font-size: 0.9rem;
        line-height: 1.4;
        word-wrap: break-word;
        white-space: pre-wrap;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        margin-bottom: 8px;
        max-width: 95%;
    }

    .message-sent {
        background-color: var(--chat-bg-sent);
        color: var(--chat-text-color);
        border-bottom-right-radius: 4px;
    }

    .message-received {
        background-color: var(--chat-bg-received);
        color: var(--chat-text-color);
        border-bottom-left-radius: 4px;
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
