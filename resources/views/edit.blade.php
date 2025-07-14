@extends('layouts.app')

@section('title', 'Modifier mon Profil - TrouveTout')

@section('content')
<div class="container my-5"> {{-- Augmentation de la marge autour du conteneur --}}
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6"> {{-- Ajustement de la largeur pour le formulaire de profil --}}
            <div class="card shadow-lg border-0"> {{-- Card avec ombre et sans bordure --}}
                <div class="card-header custom-gradient-header text-white text-center py-3"> {{-- Utilisation de notre en-tête dégradé --}}
                    <h3 class="mb-0"><i class="fas fa-user-edit me-2"></i> {{ __('Modifier mon Profil') }}</h3> {{-- Icône et texte centré --}}
                </div>
                <div class="card-body p-4"> {{-- Augmentation du padding --}}

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <h5 class="mb-1">{{ __('Erreurs de validation :') }}</h5>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Indique que c'est une requête PUT pour la mise à jour --}}

                        <div class="mb-3">
                            <label for="name" class="form-label custom-label-auth">{{ __('Nom d\'utilisateur') }} :</label>
                            <input type="text" id="name" name="name" class="form-control form-control-lg custom-form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label custom-label-auth">{{ __('Adresse Email') }} :</label>
                            <input type="email" id="email" name="email" class="form-control form-control-lg custom-form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4"> {{-- Séparateur pour les champs de mot de passe --}}

                        <div class="mb-3">
                            <label for="current_password" class="form-label custom-label-auth">{{ __('Mot de passe actuel') }} : <small class="text-muted fw-normal"> (pour confirmer les changements)</small></label>
                            <input type="password" id="current_password" name="current_password" class="form-control form-control-lg custom-form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted mt-2">{{ __('Laissez vide si vous ne souhaitez pas changer votre mot de passe.') }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label custom-label-auth">{{ __('Nouveau mot de passe') }} :</label>
                            <input type="password" id="password" name="password" class="form-control form-control-lg custom-form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4"> {{-- Marge augmentée pour le dernier champ --}}
                            <label for="password_confirmation" class="form-label custom-label-auth">{{ __('Confirmer le nouveau mot de passe') }} :</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg custom-form-control">
                        </div>

                        <div class="d-flex justify-content-end gap-2"> {{-- Alignement des boutons à droite avec espacement --}}
                            <a href="{{ route('home') }}" class="btn custom-secondary-button btn-lg"> {{-- Bouton secondaire personnalisé --}}
                                <i class="fas fa-arrow-left me-2"></i> {{ __('Retour à l\'accueil') }}
                            </a>
                            <button type="submit" class="btn custom-primary-button btn-lg"> {{-- Bouton principal personnalisé --}}
                                <i class="fas fa-save me-2"></i> {{ __('Mettre à jour le profil') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
