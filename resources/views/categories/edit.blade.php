@extends('layouts.app')

@section('title', 'Modifier la Catégorie - TrouveTout')

@section('styles')
<style>
    /* Styles généraux pour les cartes d'administration/formulaires */
    .custom-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08); /* Ombre plus douce que les cartes d'auth */
        background-color: var(--light-pure-white);
        border: none;
    }

    .custom-card .card-header {
        padding: 2.5rem 1.5rem;
        border-bottom: none;
        background: var(--gradient-primary); /* Utilise le dégradé primaire */
        color: var(--text-on-dark); /* Couleur du texte sur le dégradé */
    }

    .custom-card .card-header h3 {
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 2.2rem;
        color: var(--text-on-dark);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-card .card-header i {
        font-size: 2rem;
        margin-right: 0.75rem;
        color: var(--text-on-dark);
    }

    .custom-card .card-body {
        padding: 2rem 2.5rem;
    }

    .custom-label { /* Style générique pour les labels de formulaire */
        font-family: var(--font-body);
        font-weight: 600;
        color: var(--text-on-light);
        font-size: 1.05rem;
        margin-bottom: 0.5rem;
    }

    .custom-form-control {
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        font-size: 1.1rem;
        border: 1px solid var(--border-subtle);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.03); /* Ombre interne plus légère */
        transition: all 0.2s ease-in-out;
    }

    .custom-form-control:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.2); /* Opacité réduite pour un look plus doux */
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

    .form-text { /* Pour les petits textes d'aide */
        font-size: 0.9rem;
        color: var(--text-on-light-muted);
    }

    /* Styles pour les alertes (erreurs et succès) */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
    }

    .alert .fas {
        font-size: 1.2rem;
        margin-top: 0.15rem;
    }

    .alert > div {
        flex-grow: 1;
        padding-left: 0.75rem;
    }

    .alert ul {
        margin-bottom: 0;
        padding-left: 1.25rem;
    }

    .alert h5 { /* Pour le titre "Erreurs de validation" */
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
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
        margin-left: 1rem;
        color: inherit;
        opacity: 0.7;
    }
    .alert .btn-close:hover {
        opacity: 1;
    }

    /* Boutons personnalisés */
    .custom-primary-button {
        padding: 0.85rem 2rem; /* Légèrement plus petit pour les formulaires internes */
        font-size: 1rem;
        font-weight: 700;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        transition: all 0.2s ease-in-out;
        background-color: var(--primary-color);
        color: var(--text-on-dark);
        border: none;
        box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.2);
    }

    .custom-primary-button:hover {
        background-color: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(var(--primary-rgb), 0.3);
    }

    .custom-primary-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(var(--primary-rgb), 0.15);
    }

    .custom-secondary-button {
        padding: 0.85rem 2rem;
        font-size: 1rem;
        font-weight: 700;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        transition: all 0.2s ease-in-out;
        background-color: var(--secondary-color);
        color: var(--text-on-dark);
        border: none;
        box-shadow: 0 4px 12px rgba(var(--secondary-rgb), 0.2);
    }

    .custom-secondary-button:hover {
        background-color: var(--secondary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(var(--secondary-rgb), 0.3);
    }

    .custom-secondary-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(var(--secondary-rgb), 0.15);
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-header text-center">
                    <h3 class="mb-0"><i class="fas fa-edit me-2"></i> {{ __('Modifier la Catégorie') }}</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <h5 class="mb-1">Erreurs de validation :</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Indique que c'est une requête PUT pour la mise à jour --}}

                        <div class="mb-3">
                            <label for="Description" class="form-label custom-label">{{ __('Nom de la Catégorie') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control custom-form-control @error('Description') is-invalid @enderror" id="Description" name="Description" value="{{ old('Description', $category->Description) }}" required maxlength="20" autofocus placeholder="Ex: Électronique, Immobilier...">
                            @error('Description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text mt-2">Le nom de la catégorie (max. 20 caractères).</small>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn custom-primary-button">
                                <i class="fas fa-save me-2"></i> {{ __('Mettre à jour') }}
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn custom-secondary-button">
                                <i class="fas fa-arrow-left me-2"></i> {{ __('Retour aux Catégories') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
