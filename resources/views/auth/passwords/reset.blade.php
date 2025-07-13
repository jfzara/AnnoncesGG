@extends('layouts.app')

@section('title', 'Définir un nouveau mot de passe - TrouveTout') {{-- Titre plus spécifique --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg custom-auth-card"> {{-- Carte avec ombre et style personnalisé --}}
                <div class="card-header custom-gradient-header text-white text-center py-3">
                    <h3 class="mb-0"><i class="fas fa-key me-2"></i> {{ __('Définir un nouveau mot de passe') }}</h3> {{-- Icône et texte centré --}}
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end custom-label-auth">{{ __('Adresse e-mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control form-control-lg custom-form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Votre adresse e-mail">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end custom-label-auth">{{ __('Nouveau mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control form-control-lg custom-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Saisissez votre nouveau mot de passe">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end custom-label-auth">{{ __('Confirmer le nouveau mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control form-control-lg custom-form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmez votre nouveau mot de passe">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn custom-primary-button btn-lg"> {{-- Bouton personnalisé --}}
                                    <i class="fas fa-check me-2"></i> {{ __('Réinitialiser le mot de passe') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
