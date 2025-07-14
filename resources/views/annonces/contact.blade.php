@extends('layouts.app')

@section('title', 'Contacter l\'auteur de ' . $annonce->DescriptionAbregee)

@section('content')
<div class="container mt-4 mb-5"> {{-- Ajout de mb-5 pour plus d'espace en bas --}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card"> {{-- Suppression de shadow-sm et border-0 pour utiliser les styles de app.blade.php --}}
                <div class="card-header custom-gradient-header d-flex align-items-center"> {{-- Ajout de d-flex et align-items-center --}}
                    {{-- Icône pour une meilleure identification --}}
                    <i class="fas fa-envelope fa-lg me-3 text-white"></i> {{-- Ajout de text-white pour que l'icône soit blanche --}}
                    <h3 class="mb-0 text-white"> {{-- Changement de h1 h4 à h3 pour la cohérence, et text-white --}}
                        Contacter l'auteur de l'annonce : <span class="accent-text fw-bold">{{ Str::upper($annonce->DescriptionAbregee) }}</span> {{-- Utilisation de accent-text et Str::upper --}}
                    </h3>
                </div>

                <div class="card-body p-4"> {{-- Augmentation du padding interne pour plus d'aération --}}
                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center" role="alert"> {{-- Ajout de d-flex et align-items-center --}}
                            <i class="fas fa-check-circle me-2"></i> {{-- Ajout d'icône --}}
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center" role="alert"> {{-- Ajout de d-flex et align-items-center --}}
                            <i class="fas fa-exclamation-triangle me-2"></i> {{-- Ajout d'icône --}}
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @guest
                        <div class="alert alert-info text-center py-4"> {{-- Augmentation du padding vertical --}}
                            <i class="fas fa-info-circle fa-2x mb-3 d-block mx-auto"></i> {{-- Icône plus grande et centrée --}}
                            <h4 class="alert-heading mb-3">ACCÈS RESTREINT</h4> {{-- Nouvelle classe pour le titre d'alerte --}}
                            <p class="mb-0">Vous devez être connecté pour envoyer un message. <a href="{{ route('login') }}" class="alert-link fw-bold text-decoration-none">CONNECTEZ-VOUS ICI</a>.</p> {{-- Texte en majuscule --}}
                        </div>
                    @endguest

                    @auth
                        <form method="POST" action="{{ route('annonces.contact.send', $annonce->NoAnnonce) }}">
                            @csrf

                            <div class="mb-4"> {{-- Marge du bas augmentée --}}
                                <label for="sujet" class="form-label fw-bold">SUJET DU MESSAGE :</label> {{-- Texte en majuscule et gras --}}
                                <input type="text"
                                       class="form-control @error('sujet') is-invalid @enderror"
                                       id="sujet"
                                       name="sujet"
                                       value="{{ old('sujet') }}"
                                       placeholder="Ex: Question sur le prix ou la disponibilité"
                                       required>
                                @error('sujet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4"> {{-- Marge du bas augmentée --}}
                                <label for="message" class="form-label fw-bold">VOTRE MESSAGE :</label> {{-- Texte en majuscule et gras --}}
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message"
                                          name="message"
                                          rows="7" {{-- Augmentation des lignes pour une meilleure visibilité --}}
                                          placeholder="Écrivez votre message ici..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5"> {{-- Marge du haut augmentée --}}
                                <button type="submit" class="btn custom-primary-button">
                                    <i class="fas fa-paper-plane me-2"></i> ENVOYER LE MESSAGE
                                </button>
                                <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> RETOUR À L'ANNONCE
                                </a>
                            </div>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
{{-- Aucun style spécifique n'est nécessaire ici car ils sont tous définis dans app.blade.php ou dans les modifications locales ci-dessus --}}
<style>
    /* Styles spécifiques pour cette page si non couverts par app.blade.php ou pour les surcharges */

    .card {
        border: 2px solid var(--border-strong) !important;

        box-shadow: var(--shadow-none) !important;
        background-color: var(--light-pure-white);
    }

    .card-header.custom-gradient-header {
        background: var(--primary-dark) !important;

        border-bottom: 2px solid var(--accent-red-orange) !important;
        color: var(--text-on-dark) !important;
        padding: 1.8rem 2.2rem !important;
        display: flex;
        align-items: center;
    }

    .card-header.custom-gradient-header h3 {
        margin-bottom: 0 !important;
        color: inherit !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
    }

    .custom-primary-button {

        text-transform: uppercase !important;
        font-weight: 700 !important;
        letter-spacing: 0.05em !important;
        padding: 0.85rem 1.8rem !important;
    }

    .btn-outline-secondary {

        text-transform: uppercase !important;
        font-weight: 700 !important;
        padding: 0.85rem 1.8rem !important;
        letter-spacing: 0.04em !important;
        border-width: 2px !important;
        border-color: var(--secondary-dark) !important;
        color: var(--secondary-dark) !important;
    }
    .btn-outline-secondary:hover {
        background-color: var(--secondary-dark) !important;
        color: var(--text-on-dark) !important;
    }

    /* Alertes (messages de session) */
    .alert {

        border: 2px solid !important;
        font-weight: 500;
        padding: 1.5rem !important;
        margin-bottom: 2rem !important;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .alert-success {
        background-color: #e0ffe0 !important; /* Vert très clair */
        border-color: var(--success-color) !important;
        color: var(--success-color) !important;
    }
    .alert-danger {
        background-color: #ffe0e0 !important; /* Rouge très clair */
        border-color: var(--danger-color) !important;
        color: var(--danger-color) !important;
    }
    .alert-info {
        background-color: #e0f8ff !important; /* Bleu très clair */
        border-color: var(--info-color) !important;
        color: var(--info-color) !important;
    }
    .alert-heading {
        font-family: var(--font-heading) !important;
        color: inherit !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
    }
    .alert-link {
        font-weight: 700 !important;
        color: var(--primary-dark) !important; /* Ou une autre couleur accentuée si préférable */
    }
    .alert-link:hover {
        color: var(--accent-red-orange) !important;
        text-decoration: underline !important;
    }

    /* Formulaires */
    .form-label {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--primary-dark);
        margin-bottom: 0.8rem; /* Plus d'espace sous le label */
    }
    .form-control, .form-select {
         /* Bords carrés */
        border: 2px solid var(--border-strong) !important; /* Bordure plus visible */
        padding: 1rem 1.4rem !important;
        font-size: 1.1rem !important; /* Texte un peu plus grand */
        background-color: var(--light-pure-white) !important;
        color: var(--text-on-light) !important;
    }
    .form-control::placeholder {
        color: var(--placeholder-color) !important;
        opacity: 0.8 !important; /* Légèrement moins opaque */
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--focus-ring-color) !important;
        box-shadow: none !important; /* Pas d'ombre au focus */
    }
</style>
@endsection
