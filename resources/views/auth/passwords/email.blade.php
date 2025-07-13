@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe - TrouveTout') {{-- Titre plus spécifique --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg custom-auth-card"> {{-- Carte avec ombre et style personnalisé --}}
                <div class="card-header custom-gradient-header text-white text-center py-3">
                    <h3 class="mb-0"><i class="fas fa-lock-open me-2"></i> {{ __('Réinitialiser le mot de passe') }}</h3> {{-- Icône et texte centré --}}
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('status') }}</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end custom-label-auth">{{ __('Adresse e-mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control form-control-lg custom-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Votre adresse e-mail">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn custom-primary-button btn-lg"> {{-- Bouton personnalisé --}}
                                    <i class="fas fa-paper-plane me-2"></i> {{ __('Envoyer le lien de réinitialisation') }}
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
