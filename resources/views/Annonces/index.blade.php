@extends('layouts.app')

@section('title', 'Toutes les Annonces')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Toutes les Annonces</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @auth
            <div class="mb-3">
                <a href="{{ route('annonces.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer une nouvelle annonce
                </a>
            </div>
        @endauth

        @if ($annonces->isEmpty())
            <p class="lead">Aucune annonce disponible pour le moment.</p>
        @else
            <div class="row">
                @foreach ($annonces as $annonce)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if ($annonce->Photo) {{-- <<< CORRIGÉ : utilise $annonce->Photo --}}
                                <img src="{{ Storage::url($annonce->Photo) }}" class="card-img-top" alt="{{ $annonce->Titre }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/400x200?text=Pas+d'image" class="card-img-top" alt="Pas d'image" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $annonce->Titre }}</h5>
                                <p class="card-text text-muted small mb-1">
                                    <i class="fas fa-tag"></i> Catégorie: {{ $annonce->categorie ? $annonce->categorie->NomCategorie : 'N/A' }}
                                </p>
                                <p class="card-text text-muted small mb-2">
                                    <i class="fas fa-user"></i> Auteur: {{ $annonce->user ? $annonce->user->name : 'Utilisateur inconnu' }}
                                </p>
                                <p class="card-text">{{ Str::limit($annonce->DescriptionAbregee, 100) }}</p> {{-- <<< CORRIGÉ : utilise DescriptionAbregee --}}
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <span class="h4 mb-0 text-success">{{ number_format($annonce->Prix, 2) }} $</span>
                                    <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn btn-info btn-sm">Voir Détails</a>
                                </div>
                                <div class="mt-2 text-right">
                                    @auth
                                        @if (Auth::id() === $annonce->NoUtilisateur)
                                            {{-- Liens pour l'auteur de l'annonce --}}
                                            <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn btn-warning btn-sm me-1" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Bouton de contact pour les autres utilisateurs --}}
                                            <a href="{{ route('annonces.contact.create', $annonce->NoAnnonce) }}" class="btn btn-success btn-sm" title="Contacter l'auteur">
                                                <i class="fas fa-envelope"></i> Contacter
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $annonces->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>
@endsection
