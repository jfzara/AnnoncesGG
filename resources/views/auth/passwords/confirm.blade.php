@extends('layouts.app')

@section('title', 'Confirmer le mot de passe - TrouveTout') {{-- Titre plus spécifique --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg custom-auth-card"> {{-- Carte avec ombre et style personnalisé --}}
                <div class="card-header custom-gradient-header text-white text-center py-3">
                    <h3 class="mb-0"><i class="fas fa-lock me-2"></i> {{ __('Confirmer le mot de passe') }}</h3> {{-- Icône et texte centré --}}
                </div>

                <div class="card-body p-4">
                    <p class="text-center mb-4 text-muted">
                        {{ __('Veuillez confirmer votre mot de passe avant de continuer.') }}
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end custom-label-auth">{{ __('Mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control form-control-lg custom-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Saisissez votre mot de passe">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn custom-primary-button btn-lg"> {{-- Bouton personnalisé --}}
                                    <i class="fas fa-check-circle me-2"></i> {{ __('Confirmer le mot de passe') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link custom-link-auth ms-2" href="{{ route('password.request') }}"> {{-- Lien personnalisé --}}
                                        {{ __('Mot de passe oublié ?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
