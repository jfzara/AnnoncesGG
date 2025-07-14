@extends('layouts.app')

@section('title', 'Confirmer le mot de passe - TrouveTout')

@section('styles')
<style>
    /* Styles spécifiques pour les pages d'authentification */
    .custom-auth-card {
        border-radius: 12px; /* Coins plus arrondis pour la carte d'authentification */
        overflow: hidden; /* Assure que les coins de l'en-tête sont aussi arrondis */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); /* Ombre plus prononcée pour la carte */
        background-color: var(--light-pure-white); /* Fond blanc cassé */
        border: none; /* Pas de bordure visible */
    }

    .custom-auth-card .card-header {
        padding: 2.5rem 1.5rem; /* Plus de padding pour un en-tête plus grand */
        border-bottom: none; /* Pas de bordure sous l'en-tête */
    }

    .custom-auth-card .card-header h3 {
        font-family: var(--font-heading); /* Utilise la police des titres */
        font-weight: 700; /* Gras pour le titre */
        font-size: 2.2rem; /* Taille plus grande pour le titre */
        color: var(--text-on-dark); /* Couleur du texte sur fond dégradé */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-auth-card .card-header i {
        font-size: 2rem; /* Taille de l'icône dans le titre */
        margin-right: 0.75rem; /* Espacement entre l'icône et le texte */
        color: var(--text-on-dark); /* Couleur de l'icône, cohérente avec le texte */
    }

    .custom-auth-card .card-body {
        padding: 2rem 2.5rem; /* Padding interne de la carte */
    }

    .custom-auth-card .card-body p {
        font-size: 1.1rem; /* Taille du texte de description */
        color: var(--text-on-light-muted); /* Couleur de texte plus discrète */
        line-height: 1.6;
        margin-bottom: 2rem; /* Marge plus grande sous le paragraphe */
    }

    .custom-label-auth {
        font-family: var(--font-body); /* Police pour les labels */
        font-weight: 600; /* Plus de gras */
        color: var(--text-on-light); /* Couleur de texte principale */
        font-size: 1.05rem; /* Taille de police */
        margin-bottom: 0.5rem; /* Espacement sous le label */
        padding-top: calc(0.375rem + 1px); /* Aligne le label avec le champ de formulaire */
    }

    .custom-form-control {
        border-radius: 8px; /* Coins arrondis pour les champs de formulaire */
        padding: 0.75rem 1.25rem; /* Padding interne généreux */
        font-size: 1.1rem; /* Taille de texte dans le champ */
        border: 1px solid var(--border-subtle); /* Bordure subtile */
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05); /* Légère ombre interne */
        transition: all 0.2s ease-in-out;
    }

    .custom-form-control:focus {
        border-color: var(--primary-light); /* Bordure colorée au focus */
        box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25); /* Ombre de focus personnalisée */
        background-color: var(--light-pure-white); /* Garde le fond blanc au focus */
    }

    .custom-form-control::placeholder {
        color: var(--text-on-light-muted); /* Couleur du placeholder */
        opacity: 0.8;
    }

    .custom-form-control.is-invalid {
        border-color: var(--danger-color); /* Couleur de bordure pour les erreurs */
    }

    .invalid-feedback {
        font-size: 0.95rem; /* Taille du texte d'erreur */
        color: var(--danger-color); /* Couleur du texte d'erreur */
        margin-top: 0.5rem; /* Marge au-dessus du message d'erreur */
    }

    .custom-primary-button {
        padding: 1rem 2.5rem; /* Padding pour le bouton primaire */
        font-size: 1.15rem; /* Taille de texte du bouton */
        font-weight: 700; /* Gras */
        border-radius: 8px; /* Coins arrondis */
        text-transform: uppercase; /* Texte en majuscules */
        letter-spacing: 0.04em; /* Espacement des lettres */
        transition: all 0.2s ease-in-out;
        box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3); /* Ombre pour le bouton */
    }

    .custom-primary-button:hover {
        transform: translateY(-2px); /* Effet de léger soulèvement au survol */
        box-shadow: 0 6px 20px rgba(var(--primary-rgb), 0.4);
    }

    .custom-primary-button:active {
        transform: translateY(0); /* Retour à la position normale au clic */
        box-shadow: 0 2px 10px rgba(var(--primary-rgb), 0.2);
    }

    .custom-link-auth {
        color: var(--link-color); /* Couleur du lien personnalisée */
        font-size: 1.05rem; /* Taille du texte du lien */
        font-weight: 500;
        text-decoration: none; /* Pas de soulignement par défaut */
        transition: color 0.2s ease-in-out;
    }

    .custom-link-auth:hover {
        color: var(--link-hover-color); /* Couleur du lien au survol */
        text-decoration: underline; /* Soulignement au survol */
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card custom-auth-card">
                <div class="card-header custom-gradient-header text-center">
                    <h3 class="mb-0"><i class="fas fa-lock me-2"></i> {{ __('Confirmer le mot de passe') }}</h3>
                </div>

                <div class="card-body">
                    <p class="text-center">
                        {{ __('Veuillez confirmer votre mot de passe avant de continuer.') }}
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end custom-label-auth">{{ __('Mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control custom-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Saisissez votre mot de passe">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn custom-primary-button">
                                    <i class="fas fa-check-circle me-2"></i> {{ __('Confirmer le mot de passe') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link custom-link-auth ms-2" href="{{ route('password.request') }}">
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
