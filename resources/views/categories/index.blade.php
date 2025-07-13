@extends('layouts.app')

@section('title', 'Toutes les Catégories - TrouveTout')

@section('content')
<div class="container my-5"> {{-- Ajout de marge autour du conteneur --}}
    <h1 class="text-center custom-heading mb-4">
        <i class="fas fa-sitemap me-2"></i> {{ __('Nos Catégories') }}
    </h1> {{-- Titre central et stylisé --}}

    @auth
        <div class="d-flex justify-content-center mb-4"> {{-- Centrer le bouton --}}
            <a href="{{ route('categories.create') }}" class="btn custom-primary-button btn-lg">
                <i class="fas fa-plus-circle me-2"></i> {{ __('Créer une nouvelle catégorie') }}
            </a>
        </div>
    @endauth

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
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
        <div class="card shadow-sm"> {{-- Encapsuler le tableau dans une carte --}}
            <div class="card-body p-0"> {{-- Pas de padding pour le tableau à l'intérieur --}}
                <div class="table-responsive"> {{-- Pour rendre le tableau responsive sur petits écrans --}}
                    <table class="table table-hover mb-0"> {{-- Table avec effet hover et sans marge inférieure --}}
                        <thead class="bg-light"> {{-- En-tête de tableau avec fond clair --}}
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
                                            <div class="d-flex gap-2"> {{-- Utiliser flexbox pour espacer les boutons --}}
                                                <a href="{{ route('categories.edit', $categorie->NoCategorie) }}" class="btn custom-secondary-button btn-sm">
                                                    <i class="fas fa-edit me-1"></i> {{ __('Modifier') }}
                                                </a>
                                                <form action="{{ route('categories.destroy', $categorie->NoCategorie) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Toutes les annonces liées seront également supprimées !');">
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
