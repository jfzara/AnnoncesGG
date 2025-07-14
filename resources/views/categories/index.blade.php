@extends('layouts.app')

@section('title', 'Toutes les Catégories - TrouveTout')

@section('styles')
<style>
    /* Styles généraux pour les pages d'administration/tableaux */
    .custom-heading {
        font-family: var(--font-heading);
        font-weight: 700;
        color: var(--primary-color); /* Couleur primaire pour le titre */
        font-size: 2.8rem; /* Grande taille pour le titre principal */
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05); /* Légère ombre sur le texte */
    }

    .custom-heading i {
        font-size: 2.5rem; /* Taille de l'icône dans le titre */
        margin-right: 0.75rem;
        color: var(--primary-color);
    }

    .custom-card-table { /* Nouvelle classe pour la carte contenant le tableau */
        border-radius: 12px;
        overflow: hidden; /* Important pour que les bordures de la table s'arrondissent avec la carte */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        background-color: var(--light-pure-white);
        border: none;
    }

    .table {
        margin-bottom: 0; /* Supprime la marge par défaut des tables Bootstrap */
        border-collapse: separate; /* Permet border-radius sur les coins de table */
        border-spacing: 0;
    }

    .table thead {
        background-color: var(--primary-light); /* Fond clair pour l'en-tête de table */
        color: var(--primary-dark); /* Texte foncé pour l'en-tête */
    }

    .table th {
        font-family: var(--font-heading);
        font-weight: 600;
        padding: 1rem 1.5rem; /* Padding plus généreux */
        border-bottom: 2px solid var(--border-strong); /* Bordure inférieure plus marquée */
        text-align: left;
        vertical-align: middle;
    }

    .table td {
        padding: 1rem 1.5rem; /* Padding plus généreux pour les cellules */
        vertical-align: middle;
        border-top: 1px solid var(--border-subtle); /* Bordure fine entre les lignes */
        color: var(--text-on-light);
    }

    .table tbody tr:last-child td {
        border-bottom: none; /* Pas de bordure en bas de la dernière ligne */
    }

    .table tbody tr:hover {
        background-color: var(--background-hover); /* Effet de survol sur les lignes */
    }

    /* Styles pour les boutons d'action dans le tableau */
    .btn-action-sm { /* Boutons petits pour les actions */
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease-in-out;
        display: inline-flex; /* Pour aligner icône et texte */
        align-items: center;
        justify-content: center;
    }

    .btn-action-sm i {
        font-size: 0.9em;
        margin-right: 0.4em;
    }

    .btn-edit-custom {
        background-color: var(--secondary-color);
        color: var(--text-on-dark);
        border: none;
    }

    .btn-edit-custom:hover {
        background-color: var(--secondary-dark);
        color: var(--text-on-dark); /* Assure que le texte reste blanc/clair au survol */
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(var(--secondary-rgb), 0.2);
    }

    .btn-delete-custom {
        background-color: var(--danger-color);
        color: var(--text-on-dark);
        border: none;
    }

    .btn-delete-custom:hover {
        background-color: var(--danger-dark);
        color: var(--text-on-dark); /* Assure que le texte reste blanc/clair au survol */
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(var(--danger-rgb), 0.2);
    }

    /* Bouton "Créer une nouvelle catégorie" */
    .custom-primary-button {
        padding: 1rem 2.5rem;
        font-size: 1.15rem;
        font-weight: 700;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        transition: all 0.2s ease-in-out;
        background-color: var(--primary-color);
        color: var(--text-on-dark);
        border: none;
        box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3);
    }

    .custom-primary-button:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(var(--primary-rgb), 0.4);
    }

    .custom-primary-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(var(--primary-rgb), 0.2);
    }

    /* Styles pour les alertes (succès et info) */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start; /* Alignement en haut pour le contenu flex */
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

    .alert-success {
        background-color: var(--success-light);
        color: var(--success-dark);
        border-color: var(--success-color);
        box-shadow: 0 2px 10px rgba(var(--success-rgb), 0.1);
    }

    .alert-success .fas {
        color: var(--success-dark);
    }

    .alert-info {
        background-color: var(--info-light);
        color: var(--info-dark);
        border-color: var(--info-color);
        box-shadow: 0 2px 10px rgba(var(--info-rgb), 0.1);
    }

    .alert-info .fas {
        color: var(--info-dark);
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
</style>
@endsection

@section('content')
<div class="container my-5">
    <h1 class="custom-heading mb-4">
        <i class="fas fa-sitemap me-2"></i> {{ __('Nos Catégories') }}
    </h1>

    @auth
        <div class="d-flex justify-content-center mb-4">
            <a href="{{ route('categories.create') }}" class="btn custom-primary-button">
                <i class="fas fa-plus-circle me-2"></i> {{ __('Créer une nouvelle catégorie') }}
            </a>
        </div>
    @endauth

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($categories->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            <i class="fas fa-info-circle me-2"></i> {{ __('Aucune catégorie n\'a été trouvée pour le moment.') }}
            @auth
                <p class="mt-2 mb-0">{{ __('Cliquez sur le bouton ci-dessus pour en ajouter une nouvelle.') }}</p>
            @endauth
        </div>
    @else
        <div class="card custom-card-table">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('ID') }}</th>
                                <th scope="col">{{ __('Description') }}</th>
                                <th scope="col">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $categorie)
                                <tr>
                                    <td>{{ $categorie->NoCategorie }}</td>
                                    <td>{{ $categorie->Description }}</td>
                                    <td>
                                        @auth
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('categories.edit', $categorie->NoCategorie) }}" class="btn btn-action-sm btn-edit-custom">
                                                    <i class="fas fa-edit me-1"></i> {{ __('Modifier') }}
                                                </a>
                                                <form action="{{ route('categories.destroy', $categorie->NoCategorie) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-action-sm btn-delete-custom" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Toutes les annonces liées seront également supprimées !');">
                                                        <i class="fas fa-trash-alt me-1"></i> {{ __('Supprimer') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endauth
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
