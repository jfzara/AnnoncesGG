@extends('layouts.app')

@section('title', 'Conversation avec ' . ($otherUser->name ?? 'Utilisateur inconnu'))

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Conversation avec {{ $otherUser->name ?? 'Utilisateur inconnu' }}</h1>
        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux conversations
        </a>
    </div>

    {{-- Informations sur l'annonce --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Annonce concernée : <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="text-white text-decoration-none">{{ $annonce->Titre }}</a></h5>
        </div>
        <div class="card-body">
            <p class="card-text">{{ Str::limit($annonce->DescriptionAbregee, 150) }}</p>
            <p class="card-text mb-0"><small class="text-muted">Prix : {{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit / À discuter' }}</small></p>
        </div>
    </div>

    {{-- Messages de la conversation --}}
    <div class="message-container border rounded p-3 mb-4 bg-light" style="max-height: 500px; overflow-y: auto;">
        @if ($messages->isEmpty())
            <div class="alert alert-info text-center">
                Aucun message dans cette conversation. Commencez la discussion !
            </div>
        @else
            @foreach ($messages as $message)
                @php
                    // Déterminer si le message a été envoyé par l'utilisateur connecté
                    $isSender = ($message->sender_id === Auth::id());
                    $messageClass = $isSender ? 'bg-success text-white ms-auto' : 'bg-primary text-white me-auto'; // Vert pour l'expéditeur, Bleu pour le destinataire
                    $alignment = $isSender ? 'text-end' : 'text-start'; // Alignement à droite pour l'expéditeur
                @endphp
                <div class="d-flex {{ $alignment }} mb-2">
                    <div class="card {{ $messageClass }}" style="max-width: 75%;">
                        <div class="card-body p-2">
                            <p class="card-text mb-1">{{ $message->content }}</p>
                            <small class="text-light opacity-75">
                                {{ $message->created_at->format('d/m/Y H:i') }}
                                @if ($isSender && $message->read_at_receiver)
                                    <i class="fas fa-check-double text-info ms-1" title="Lu par {{ $otherUser->name }}"></i>
                                @elseif (!$isSender && $message->read_at_sender && $message->read_at_receiver)
                                    {{-- Ceci ne devrait pas arriver si le récepteur est l'utilisateur courant et qu'il vient de lire --}}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Formulaire de réponse --}}
    <div class="card p-3 shadow-sm">
        <h5 class="mb-3">Envoyer un message</h5>
        <form action="{{ route('messages.store', $annonce->NoAnnonce) }}" method="POST">
            @csrf
            {{-- Champ caché pour l'ID du destinataire --}}
            <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">

            <div class="mb-3">
                <textarea name="content" id="content" class="form-control" rows="3" placeholder="Écrivez votre message ici..." required></textarea>
                @error('content')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Envoyer le message</button>
        </form>
    </div>
</div>
@endsection
