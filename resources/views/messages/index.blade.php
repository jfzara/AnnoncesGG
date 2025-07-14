@extends('layouts.app')

@section('title', 'Mes Conversations - TrouveTout')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="custom-heading mb-0"><i class="fas fa-comments me-3"></i> {{ __('Mes Conversations') }}</h1> {{-- Titre stylisé et icône --}}
        <a href="{{ route('annonces.index') }}" class="btn custom-secondary-button btn-lg"> {{-- Bouton secondaire stylisé --}}
            <i class="fas fa-search me-2"></i>{{ __('Parcourir les annonces') }}
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($conversations->isEmpty())
        <div class="alert alert-info text-center p-4 shadow-sm rounded"> {{-- Ajout d'ombre et bordure arrondie --}}
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
        <div class="list-group shadow-sm"> {{-- Ajout d'ombre à la liste --}}
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
                   class="list-group-item list-group-item-action border-bottom py-3 custom-conversation-item {{ $hasUnread ? 'custom-unread-conversation' : '' }}">
                    <div class="d-flex w-100 align-items-center">
                        <img src="{{ $avatarUrl }}" class="rounded-circle me-3 custom-avatar-sm" alt="{{ $otherParticipant->name }}">

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="mb-0 {{ $hasUnread ? 'fw-bold text-primary' : 'text-dark' }}"> {{-- Nom du participant en gras/bleu si non lu --}}
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
                            <span class="badge bg-danger rounded-pill ms-3 fs-6 p-2 custom-unread-badge">
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

@section('styles')
<style>
    /* Styles spécifiques à cette page */
    .custom-unread-conversation {
        background-color: var(--bs-light-blue); /* Une couleur de fond douce pour les conversations non lues */
        border-left: 5px solid var(--bs-primary) !important; /* Bordure plus épaisse pour attirer l'attention */
    }

    .custom-unread-badge {
        font-size: 0.85em;
        padding: 0.4em 0.7em;
    }

    .custom-conversation-item:hover {
        background-color: var(--bs-light) !important; /* Changer le fond au survol */
        transform: translateY(-2px); /* Léger effet de soulèvement */
        transition: all 0.2s ease-in-out;
    }

    .custom-avatar-sm {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border: 2px solid var(--bs-light);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>
@endsection
