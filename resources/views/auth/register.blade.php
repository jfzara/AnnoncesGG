@extends('layouts.app')

@section('title', 'Inscription - TrouveTout')

@section('styles')
<style>
    /* Styles spécifiques pour les pages d'authentification (réutilisés) */
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

    .custom-auth-card .card-header h4 { /* Utilisez h4 ici comme dans le code original */
        font-family: var(--font-heading); /* Utilise la police des titres */
        font-weight: 700; /* Gras pour le titre */
        font-size: 2rem; /* Taille plus grande pour le titre */
        color: var(--text-on-dark); /* Couleur du texte sur fond dégradé */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-auth-card .card-header i {
        font-size: 1.8rem; /* Taille de l'icône dans le titre */
        margin-right: 0.75rem; /* Espacement entre l'icône et le texte */
        color: var(--text-on-dark); /* Couleur de l'icône, cohérente avec le texte */
    }

    .custom-auth-card .card-body {
        padding: 2rem 2.5rem; /* Padding interne de la carte */
    }

    .custom-label-auth {
        font-family: var(--font-body); /* Police pour les labels */
        font-weight: 600; /* Plus de gras */
        color: var(--text-on-light); /* Couleur de texte principale */
        font-size: 1.05rem; /* Taille de police */
        margin-bottom: 0.5rem; /* Espacement sous le label */
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

    /* Styles pour les messages de session (alertes) */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        margin-bottom: 2rem; /* Marge en bas pour l'alerte */
        display: flex;
        align-items: flex-start; /* Alignement en haut pour le contenu flex */
    }

    .alert .fas {
        font-size: 1.2rem;
        margin-top: 0.15rem; /* Ajustement vertical pour l'icône */
    }

    .alert > div { /* Conteneur du texte du message */
        flex-grow: 1; /* Permet au texte de prendre l'espace restant */
        padding-left: 0.75rem; /* Espacement entre icône et texte */
    }

    .alert ul {
        margin-bottom: 0;
        padding-left: 1.25rem; /* Indentation des listes d'erreurs */
    }

    .alert-danger {
        background-color: var(--danger-light);
        color: var(--danger-dark);
        border-color: var(--danger-color);
        box-shadow: 0 2px 10px rgba(var(--danger-rgb), 0.1);
    }

    .alert-danger .fas {
        color: var(--danger-dark);
    }

    .alert .btn-close {
        font-size: 0.8rem;
        padding: 0;
        margin-left: 1rem; /* Espacement à gauche du bouton de fermeture */
        color: inherit; /* Utilise la couleur du texte de l'alerte */
        opacity: 0.7;
    }
    .alert .btn-close:hover {
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card custom-auth-card">
                <div class="card-header custom-gradient-header text-center">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> {{ __('Créez votre compte') }}</h4>
                </div>
                <div class="card-body">
                    {{-- Affichage des messages d'erreur de validation --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label custom-label-auth">{{ __('Nom d\'utilisateur') }}</label>
                            <input id="name" type="text" class="form-control custom-form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Votre nom d'utilisateur">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label custom-label-auth">{{ __('Adresse E-mail') }}</label>
                            <input id="email" type="email" class="form-control custom-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Votre adresse e-mail">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label custom-label-auth">{{ __('Mot de passe') }}</label>
                            <input id="password" type="password" class="form-control custom-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Créez un mot de passe">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label custom-label-auth">{{ __('Confirmer le mot de passe') }}</label>
                            <input id="password_confirmation" type="password" class="form-control custom-form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmez votre mot de passe">
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn custom-primary-button">
                                <i class="fas fa-user-plus me-2"></i> {{ __('S\'inscrire') }}
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0 text-muted">Déjà un compte ? <a href="{{ route('login') }}" class="custom-link-auth fw-bold">Connectez-vous ici</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
