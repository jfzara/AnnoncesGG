@extends('layouts.app') {{-- Assurez-vous que votre layout principal est 'layouts.app' --}}

@section('title', 'Modification du profil - TrouveTout') {{-- Titre mis à jour --}}

@section('content')
<div class="container my-5"> {{-- Ajout de marges globales --}}
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8"> {{-- Ajustement de la largeur pour un meilleur équilibre --}}
            <div class="card shadow-lg custom-auth-card border-0"> {{-- Utilisation de nos classes stylisées --}}
                <div class="card-header custom-gradient-header text-white text-center py-3">
                    <h3 class="mb-0"><i class="fas fa-user-circle me-2"></i> {{ __('Modification du profil') }}</h3>
                </div>

                <div class="card-body p-4"> {{-- Augmentation du padding --}}
                    <p class="lead text-center text-muted mb-4">
                        {{ __('Mettez à jour vos informations personnelles et votre mot de passe ici.') }}
                    </p>

                    <hr class="my-4">

                    {{-- Messages de session (succès) --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Messages d'erreur de validation --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT') {{-- Laravel utilise PUT pour les mises à jour --}}

                        <div class="mb-3"> {{-- Utilisation de mb-3 pour l'espacement --}}
                            <label for="name" class="form-label custom-label-auth">{{ __('Nom') }}</label>
                            <input type="text" class="form-control custom-form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name" autofocus>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label custom-label-auth">{{ __('Adresse Email') }}</label>
                            <input type="email" class="form-control custom-form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr class="my-4 border-secondary opacity-25"> {{-- Ligne de séparation stylisée --}}

                        <h4 class="mb-3 text-secondary"><i class="fas fa-key me-2"></i> {{ __('Changer le mot de passe (optionnel)') }}</h4>

                        <div class="mb-3">
                            <label for="current_password" class="form-label custom-label-auth">{{ __('Mot de passe actuel') }}</label>
                            <input type="password" class="form-control custom-form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" autocomplete="current-password">
                            <div class="form-text text-muted small">
                                {{ __('Requis si vous modifiez l\'email, le nom ou le mot de passe.') }}
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label custom-label-auth">{{ __('Nouveau mot de passe') }}</label>
                            <input type="password" class="form-control custom-form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                            <div class="form-text text-muted small">
                                {{ __('Minimum 8 caractères. Laissez vide si vous ne souhaitez pas changer votre mot de passe.') }}
                            </div>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4"> {{-- mb-4 pour plus d'espace avant le bouton --}}
                            <label for="password_confirmation" class="form-label custom-label-auth">{{ __('Confirmer le nouveau mot de passe') }}</label>
                            <input type="password" class="form-control custom-form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        </div>

                        <div class="d-grid gap-2"> {{-- Utilisation de d-grid gap-2 pour un bouton plein largeur --}}
                            <button type="submit" class="btn custom-primary-button btn-lg">
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
