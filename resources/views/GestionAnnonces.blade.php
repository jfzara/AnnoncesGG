@extends('layouts.app') {{-- Assurez-vous que votre layout principal est 'layouts.app' --}}

@section('title', 'Gestion de vos annonces - TrouveTout') {{-- Titre mis à jour --}}

@section('content')
<div class="container my-5"> {{-- Ajout de marges globales --}}
    <div class="row justify-content-center"> {{-- Centrer le contenu --}}
        <div class="col-lg-10"> {{-- Augmenter légèrement la largeur pour une meilleure visibilité --}}
            <h1 class="custom-heading text-center mb-4"><i class="fas fa-bullhorn me-3"></i> {{ __('Gestion de vos annonces') }}</h1> {{-- Titre stylisé et icône --}}

            <p class="lead text-center text-muted mb-4">
                {{ __('Bienvenue sur votre espace personnel. Ici, vous pouvez consulter toutes vos annonces, en ajouter de nouvelles, ou modifier et supprimer celles qui existent déjà.') }}
            </p>

            <hr class="my-4"> {{-- Séparateur avec marges --}}

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm mb-5"> {{-- Ombre et marge inférieure --}}
                <div class="card-header custom-gradient-header text-white py-3 d-flex justify-content-between align-items-center"> {{-- En-tête dégradé avec flexbox --}}
                    <h4 class="mb-0"><i class="fas fa-list-alt me-2"></i> {{ __('Vos annonces actuelles') }}</h4>
                    <a href="{{ route('annonces.create') }}" class="btn custom-secondary-button btn-sm"> {{-- Bouton Ajouter stylisé --}}
                        <i class="fas fa-plus-circle me-2"></i> {{ __('Publier une annonce') }}
                    </a>
                </div>
                <div class="card-body p-0"> {{-- Pas de padding pour un tableau pleine largeur --}}
                    {{-- Si $annonces est vide --}}
                    @if ($annonces->isEmpty()) {{-- Supposons que vous passez une collection $annonces vide ou non --}}
                        <div class="alert alert-info text-center p-4 mb-0 rounded-0"> {{-- Alert sans bordure pour s'intégrer --}}
                            <h5 class="alert-heading mb-3"><i class="fas fa-info-circle me-2"></i> {{ __('Aucune annonce trouvée') }}</h5>
                            <p class="mb-0">{{ __('Il semblerait que vous n\'ayez pas encore publié d\'annonces.') }}</p>
                            <p class="mb-0">{{ __('Cliquez sur le bouton "Publier une annonce" ci-dessus pour commencer !') }}</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0"> {{-- Table avec effet hover et sans marge inférieure --}}
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">{{ __('Titre') }}</th>
                                        <th scope="col">{{ __('Catégorie') }}</th>
                                        <th scope="col" class="text-center">{{ __('Prix') }}</th>
                                        <th scope="col" class="text-center">{{ __('Statut') }}</th>
                                        <th scope="col" class="text-center">{{ __('Date') }}</th>
                                        <th scope="col" class="text-center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($annonces as $annonce)
                                        <tr>
                                            <td>
                                                <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="text-decoration-none fw-semibold">
                                                    {{ Str::limit($annonce->Titre, 40) }}
                                                </a>
                                            </td>
                                            <td>{{ $annonce->categorie->Description ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $annonce->Prix }} $</td>
                                            <td class="text-center">
                                                @if ($annonce->Statut === 'active')
                                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> {{ __('Active') }}</span>
                                                @elseif ($annonce->Statut === 'pending')
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> {{ __('En attente') }}</span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="fas fa-archive me-1"></i> {{ __('Archivée') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">{{ $annonce->created_at->format('d/m/Y') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn btn-sm custom-edit-button" title="{{ __('Modifier l\'annonce') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm custom-delete-button" title="{{ __('Supprimer l\'annonce') }}" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est irréversible.') }}');">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                    {{-- Bouton pour désactiver/activer l'annonce (si applicable) --}}
                                                    @if ($annonce->Statut === 'active')
                                                        <form action="{{ route('annonces.deactivate', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ __('Désactiver l\'annonce') }}" onclick="return confirm('{{ __('Voulez-vous vraiment désactiver cette annonce ? Elle ne sera plus visible publiquement.') }}');">
                                                                <i class="fas fa-power-off"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('annonces.activate', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('Activer l\'annonce') }}" onclick="return confirm('{{ __('Voulez-vous vraiment réactiver cette annonce ? Elle redeviendra visible publiquement.') }}');">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm"> {{-- Ombre pour la carte des stats --}}
                <div class="card-header custom-gradient-header text-white py-3"> {{-- En-tête dégradé --}}
                    <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i> {{ __('Statistiques rapides') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="p-3 border rounded h-100 d-flex flex-column justify-content-center align-items-center bg-light">
                                <i class="fas fa-boxes fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">{{ __('Total d\'annonces') }}</h5>
                                <p class="fs-4 fw-bold text-primary">{{ $totalAnnonces ?? 0 }}</p> {{-- Utiliser les vraies données --}}
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="p-3 border rounded h-100 d-flex flex-column justify-content-center align-items-center bg-light">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h5 class="mb-1">{{ __('Annonces actives') }}</h5>
                                <p class="fs-4 fw-bold text-success">{{ $activeAnnonces ?? 0 }}</p> {{-- Utiliser les vraies données --}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded h-100 d-flex flex-column justify-content-center align-items-center bg-light">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h5 class="mb-1">{{ __('Annonces en attente') }}</h5>
                                <p class="fs-4 fw-bold text-warning">{{ $pendingAnnonces ?? 0 }}</p> {{-- Utiliser les vraies données --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styles spécifiques pour cette page */
    .custom-edit-button,
    .custom-delete-button {
        padding: 0.375rem 0.6rem; /* Plus petit pour les icônes */
        font-size: 0.875rem; /* Taille de police réduite */
    }

    .custom-edit-button {
        background-color: var(--bs-info);
        color: white;
        border-color: var(--bs-info);
    }

    .custom-edit-button:hover {
        background-color: var(--bs-info-dark); /* Assurez-vous d'avoir une variable pour un info plus foncé ou une couleur directe */
        border-color: var(--bs-info-dark);
    }

    .custom-delete-button {
        background-color: var(--bs-danger);
        color: white;
        border-color: var(--bs-danger);
    }

    .custom-delete-button:hover {
        background-color: var(--bs-danger-dark); /* Assurez-vous d'avoir une variable pour un danger plus foncé ou une couleur directe */
        border-color: var(--bs-danger-dark);
    }
</style>
@endsection
