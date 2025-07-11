@extends('layouts.app')

@section('title', 'Contacter le vendeur pour ' . $annonce->Titre)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title mb-0">Contacter le vendeur : {{ $annonce->user->name ?? 'Utilisateur inconnu' }}</h2>
                </div>
                <div class="card-body">
                    <p class="lead">Annonce concernée : <strong><a href="{{ route('annonces.show', $annonce->NoAnnonce) }}">{{ $annonce->Titre }}</a></strong></p>
                    <hr>

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

                    <form action="{{ route('messages.store', $annonce->NoAnnonce) }}" method="POST">
                        @csrf
                        {{-- Ajout d'un champ caché pour le receiver_id, car le store attend maintenant ce paramètre --}}
                        <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">

                        <div class="mb-3">
                            <label for="content" class="form-label">Votre message :</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" placeholder="Écrivez votre message ici..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Envoyer le message <i class="fas fa-paper-plane"></i></button>
                            <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
