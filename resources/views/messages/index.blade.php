@extends('layouts.app')

@section('title', 'Mes Conversations')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes Conversations</h1>
        <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-search me-2"></i>Parcourir les annonces
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($conversations->isEmpty())
        <div class="alert alert-info text-center p-4">
            <p class="lead mb-0">
                Vous n'avez aucune conversation pour le moment.
            </p>
            <p class="mb-0">
                Trouvez une annonce qui vous intéresse pour en initier une !
            </p>
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
                    // Assurez-vous que le chemin 'images/default_avatar.png' est correct dans votre dossier public.
                    $avatarUrl = $otherParticipant->profile_picture_url ?? asset('images/default_avatar.png');
                @endphp

                <a href="{{ route('messages.show', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $otherParticipant->id]) }}"
                   class="list-group-item list-group-item-action border-bottom py-3 {{ $hasUnread ? 'list-group-item-light-primary border-primary' : '' }}">
                    <div class="d-flex w-100 align-items-center">
                        <img src="{{ $avatarUrl }}" class="rounded-circle me-3" alt="{{ $otherParticipant->name }}" style="width: 50px; height: 50px; object-fit: cover;">

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="mb-0 {{ $hasUnread ? 'fw-bold text-dark' : 'text-primary' }}">
                                    {{ $otherParticipant->name ?? 'Utilisateur inconnu' }}
                                </h5>
                                <small class="text-muted {{ $hasUnread ? 'fw-bold' : '' }}">
                                    {{ $lastMessage->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <p class="mb-0 text-truncate {{ $hasUnread ? 'fw-bold text-dark' : 'text-secondary' }}" style="max-width: 90%;">
                                <small class="d-block text-muted mb-1">
                                    <i class="fas fa-tag me-1"></i>
                                    À propos de : <span class="fw-semibold">{{ Str::limit($annonce->Titre, 35) }}</span>
                                </small>
                                <span class="{{ $displayBoldLastMessage ? 'fw-bold' : '' }}">
                                    @if ($lastMessage->sender_id === $currentUser->id)
                                        Vous :
                                    @else
                                        {{ Str::limit($otherParticipant->name ?? 'Quelqu\'un', 10) }} :
                                    @endif
                                </span>
                                {{ Str::limit($lastMessage->content, 60) }}
                            </p>
                        </div>

                        @if ($hasUnread)
                            <span class="badge bg-danger rounded-pill ms-3 fs-6 p-2">
                                {{ $conversation->unreadCount }}
                                <span class="visually-hidden">messages non lus</span>
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .list-group-item-light-primary {
        background-color: #e9f0ff; /* Une couleur de fond douce pour les conversations non lues */
        border-left: 5px solid var(--bs-primary) !important; /* Bordure plus épaisse pour attirer l'attention */
    }
</style>
@endsection
