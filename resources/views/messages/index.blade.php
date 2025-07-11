@extends('layouts.app')

@section('title', 'Ma Messagerie')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Ma Messagerie</h1>

    {{-- Messages Flash (inchangés) --}}
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
        <div class="alert alert-info text-center">
            Vous n'avez aucune conversation pour le moment. Trouvez une annonce pour en initier une !
        </div>
    @else
        <div class="list-group">
            @foreach ($conversations as $conversation)
                @php
                    $currentUser = Auth::user();
                    $otherParticipant = ($conversation->sender_id === $currentUser->id)
                                        ? $conversation->receiver
                                        : $conversation->sender;
                    $annonce = $conversation->annonce;

                    // Vérifier si la conversation a des messages non lus pour l'utilisateur actuel
                    // C'est le cas si l'utilisateur est le receveur du DERNIER message et qu'il n'est pas lu.
                    $hasUnread = ($conversation->receiver_id === $currentUser->id && !$conversation->read_at_receiver);
                @endphp

                <a href="{{ route('messages.show', ['annonce' => $annonce->NoAnnonce, 'otherUserId' => $otherParticipant->id]) }}"
                   class="list-group-item list-group-item-action py-3 @if($hasUnread) list-group-item-info @else bg-white @endif d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-user-circle me-2"></i> Conversation avec
                            <strong>{{ $otherParticipant->name ?? 'Utilisateur inconnu' }}</strong>
                        </h5>
                        <p class="mb-1 text-muted small">
                            <i class="fas fa-tag me-1"></i> Annonce: {{ $annonce->Titre ?? 'Annonce supprimée' }}
                        </p>
                        <p class="mb-0 text-dark">
                            <i class="fas fa-comment-dots me-1"></i> {{ Str::limit($conversation->content, 80) }}
                        </p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block mb-1">
                            <i class="fas fa-clock me-1"></i> {{ $conversation->created_at->diffForHumans() }}
                        </small>
                        @if ($hasUnread)
                            <span class="badge bg-danger rounded-pill">Nouveau !</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        {{-- Vous pouvez ajouter de la pagination ici si $conversations est un paginateur --}}
        {{-- <div class="d-flex justify-content-center mt-4">
            {{ $conversations->links() }}
        </div> --}}
    @endif
</div>
@endsection
