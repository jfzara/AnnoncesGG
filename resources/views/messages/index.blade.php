@extends('layouts.app')

@section('title', 'Mes Conversations')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes Conversations</h1>
        <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-search"></i> Parcourir les annonces
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($conversations->isEmpty())
        <div class="alert alert-info text-center">
            Vous n'avez aucune conversation pour le moment. Trouvez une annonce pour en initier une !
        </div>
    @else
        <div class="list-group">
            @foreach ($conversations as $conversation)
                @php
                    $currentUser = Auth::user();
                    // L'otherUser est directement disponible sur l'objet conversation que nous avons créé
                    $otherParticipant = $conversation->otherUser;
                    $annonce = $conversation->annonce;
                    $lastMessage = $conversation->lastMessage;

                    // Vérifier si la conversation a des messages non lus pour l'utilisateur actuel
                    // (le 'unreadCount' est déjà calculé dans le contrôleur)
                    $hasUnread = $conversation->unreadCount > 0;
                @endphp
                <a href="{{ route('messages.show', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $otherParticipant->id]) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $hasUnread ? 'list-group-item-info' : '' }}">
                    <div class="d-flex w-100 justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                Conversation avec <span class="text-primary">{{ $otherParticipant->name ?? 'Utilisateur inconnu' }}</span>
                                <small class="text-muted d-block">à propos de "{{ Str::limit($annonce->Titre, 40) }}"</small>
                            </h5>
                            <p class="mb-1 text-muted">
                                Dernier message :
                                <span class="text-dark">
                                    @if ($lastMessage->sender_id === $currentUser->id)
                                        Vous :
                                    @else
                                        {{ $otherParticipant->name ?? 'Quelqu\'un' }} :
                                    @endif
                                </span>
                                {{ Str::limit($lastMessage->content, 80) }}
                            </p>
                        </div>
                        <small class="text-muted text-end">
                            {{ $lastMessage->created_at->diffForHumans() }}
                            @if ($hasUnread)
                                <span class="badge bg-danger rounded-pill ms-2">{{ $conversation->unreadCount }}</span>
                            @endif
                        </small>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
