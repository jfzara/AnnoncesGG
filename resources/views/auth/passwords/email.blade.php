@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe - TrouveTout')

@section('styles')
<style>
    /* Styles spécifiques pour les pages d'authentification (réutilisés de confirm-password.blade.php) */
    .custom-auth-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        background-color: var(--light-pure-white);
        border: none;
    }

    .custom-auth-card .card-header {
        padding: 2.5rem 1.5rem;
        border-bottom: none;
    }

    .custom-auth-card .card-header h3 {
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 2.2rem;
        color: var(--text-on-dark);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-auth-card .card-header i {
        font-size: 2rem;
        margin-right: 0.75rem;
        color: var(--text-on-dark);
    }

    .custom-auth-card .card-body {
        padding: 2rem 2.5rem;
    }

    .custom-auth-card .card-body p {
        font-size: 1.1rem;
        color: var(--text-on-light-muted);
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .custom-label-auth {
        font-family: var(--font-body);
        font-weight: 600;
        color: var(--text-on-light);
        font-size: 1.05rem;
        margin-bottom: 0.5rem;
        padding-top: calc(0.375rem + 1px);
    }

    .custom-form-control {
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        font-size: 1.1rem;
        border: 1px solid var(--border-subtle);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease-in-out;
    }

    .custom-form-control:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25);
        background-color: var(--light-pure-white);
    }

    .custom-form-control::placeholder {
        color: var(--text-on-light-muted);
        opacity: 0.8;
    }

    .custom-form-control.is-invalid {
        border-color: var(--danger-color);
    }

    .invalid-feedback {
        font-size: 0.95rem;
        color: var(--danger-color);
        margin-top: 0.5rem;
    }

    .custom-primary-button {
        padding: 1rem 2.5rem;
        font-size: 1.15rem;
        font-weight: 700;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3);
    }

    .custom-primary-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(var(--primary-rgb), 0.4);
    }

    .custom-primary-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(var(--primary-rgb), 0.2);
    }

    /* Styles pour les messages de session (alertes) */
    .alert-success {
        background-color: var(--success-light); /* Fond clair de succès */
        color: var(--success-dark); /* Texte foncé de succès */
        border-color: var(--success-color); /* Bordure de succès */
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        margin-bottom: 2rem; /* Marge en bas pour l'alerte */
        box-shadow: 0 2px 10px rgba(var(--success-rgb), 0.1); /* Légère ombre */
    }

    .alert-success .fas {
        color: var(--success-dark); /* Icône de succès */
        font-size: 1.2rem;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card custom-auth-card">
                <div class="card-header custom-gradient-header text-center">
                    <h3 class="mb-0"><i class="fas fa-lock-open me-2"></i> {{ __('Réinitialiser le mot de passe') }}</h3>
                </div>

                <div class="card-body">
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
                                <input id="email" type="email" class="form-control custom-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Votre adresse e-mail">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn custom-primary-button">
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
