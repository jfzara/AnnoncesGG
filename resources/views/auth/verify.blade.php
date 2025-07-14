@extends('layouts.app')

@section('title', 'Vérifiez votre adresse e-mail - TrouveTout')

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

    .custom-auth-card .card-body p {
        font-size: 1.1rem; /* Taille du texte de description */
        color: var(--text-on-light-muted); /* Couleur de texte plus discrète */
        line-height: 1.6;
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

    .alert-success {
        background-color: var(--success-light);
        color: var(--success-dark);
        border-color: var(--success-color);
        box-shadow: 0 2px 10px rgba(var(--success-rgb), 0.1);
    }

    .alert-success .fas {
        color: var(--success-dark);
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
        <div class="col-md-8">
            <div class="card custom-auth-card">
                <div class="card-header custom-gradient-header text-center">
                    <h4 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i> {{ __('Vérifiez votre adresse e-mail') }}</h4>
                </div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class="text-center mb-4">
                        {{ __('Avant de continuer, veuillez vérifier votre boîte de réception pour un lien de vérification.') }}
                    </p>
                    <p class="text-center mb-0">
                        {{ __('Si vous n\'avez pas reçu l\'e-mail') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link custom-link-auth p-0 m-0 align-baseline fw-bold">{{ __('cliquez ici pour en demander un autre') }}</button>.
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
