@extends('layouts.app')

@section('title', 'Contacter le vendeur pour "' . $annonce->Titre . '" - TrouveTout') {{-- Titre plus spécifique --}}

@section('content')
<div class="container my-5"> {{-- Augmentation de la marge autour du conteneur --}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0"> {{-- Card avec ombre et sans bordure --}}
                <div class="card-header custom-gradient-header text-white text-center py-3"> {{-- Utilisation de notre en-tête dégradé --}}
                    <h3 class="mb-0"><i class="fas fa-envelope me-2"></i> {{ __('Contacter le vendeur') }} : <span class="fw-bold">{{ $annonce->user->name ?? 'Utilisateur inconnu' }}</span></h3> {{-- Icône et texte centré --}}
                </div>
                <div class="card-body p-4"> {{-- Augmentation du padding --}}
                    <p class="lead text-center mb-4 text-muted">
                        {{ __('Annonce concernée') }} : <br>
                        <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="custom-link-auth fw-bold fs-5">"{{ $annonce->Titre }}"</a>
                    </p>
                    <hr class="mb-4"> {{-- Séparateur --}}

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

                    <form action="{{ route('messages.store', $annonce->NoAnnonce) }}" method="POST">
                        @csrf
                        {{-- Champ caché pour le receiver_id --}}
                        <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">

                        <div class="mb-4"> {{-- Marge augmentée --}}
                            <label for="content" class="form-label custom-label-auth">{{ __('Votre message') }} :</label>
                            <textarea class="form-control form-control-lg custom-form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8" placeholder="Écrivez ici votre message au vendeur. Soyez clair et concis..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted mt-2">Votre message sera envoyé directement au vendeur de l'annonce.</small>
                        </div>
                        <div class="d-flex justify-content-end gap-2"> {{-- Alignement des boutons à droite avec espacement --}}
                            <button type="submit" class="btn custom-primary-button btn-lg"> {{-- Bouton principal personnalisé --}}
                                <i class="fas fa-paper-plane me-2"></i> {{ __('Envoyer le message') }}
                            </button>
                            <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn custom-secondary-button btn-lg"> {{-- Bouton secondaire personnalisé --}}
                                <i class="fas fa-times-circle me-2"></i> {{ __('Annuler') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
