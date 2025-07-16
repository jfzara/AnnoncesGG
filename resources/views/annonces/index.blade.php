@extends('layouts.app')

@section('title', 'Toutes les Annonces - TrouveTout') {{-- Nom de l'application plus spécifique --}}

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            {{-- En-tête de la section des annonces --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-5 custom-heading mb-0">
                    <i class="fas fa-search me-3"></i> Parcourir les Annonces
                </h1>
                @auth
                    {{-- Bouton pour créer une annonce (visible uniquement si authentifié) --}}
                    <a href="{{ route('annonces.create') }}" class="btn custom-primary-button">
                        <i class="fas fa-plus-circle me-2"></i> Créer une annonce
                    </a>
                @endauth
            </div>

            {{-- Messages Flash --}}
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

            <hr class="mb-4">

            {{-- Formulaire de recherche et filtre --}}
            <div class="card p-3 mb-5 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3"><i class="fas fa-filter me-2"></i>Filtres de recherche</h5>
                    <form action="{{ route('annonces.index') }}" method="GET">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label for="search" class="visually-hidden">Rechercher</label>
                                <input type="text" name="search" id="search" class="form-control form-control-lg custom-form-control" placeholder="Rechercher par titre ou description..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="category" class="visually-hidden">Catégorie</label>
                                <select name="category" id="category" class="form-select form-select-lg custom-form-control">
                                    <option value="all">Toutes les catégories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->NoCategorie }}" {{ request('category') == $cat->NoCategorie ? 'selected' : '' }}>
                                            {{ $cat->Description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn custom-primary-button btn-lg w-100">
                                    <i class="fas fa-search me-2"></i> Filtrer
                                </button>
                            </div>
                            {{-- Bouton pour effacer les filtres si la recherche ou la catégorie est active --}}
                            @if(request('search') || (request('category') && request('category') !== 'all'))
                                <div class="col-12 text-end mt-2">
                                    <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times-circle me-2"></i> Effacer les filtres
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            ---

            {{-- Affichage des annonces --}}
            @if ($annonces->isEmpty())
                <div class="alert alert-info text-center py-5 shadow-sm rounded-3">
                    <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                    <h4 class="alert-heading">Aucune annonce trouvée</h4>
                    <p class="mb-0">Désolé, aucune annonce ne correspond à vos critères de recherche.</p>
                    <p class="mb-0 mt-1">
                        Essayez une autre recherche, <a href="{{ route('annonces.index') }}" class="alert-link">réinitialisez les filtres</a>
                        ou <a href="{{ route('annonces.create') }}" class="alert-link">créez votre première annonce</a> dès maintenant !
                    </p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($annonces as $annonce)
                        <div class="col">
                            <div class="card h-100 shadow-sm custom-card-hover border-0"> {{-- Ajout de la classe de survol --}}
                                @if ($annonce->Photo)
                                    {{-- MODIFICATION MAJEURE ICI : Utilisation directe de l'URL Cloudinary --}}
                                    <img src="{{ $annonce->Photo }}" class="card-img-top custom-card-img" alt="{{ $annonce->Titre }}">
                                @else
                                    {{-- Le placeholder reste avec asset() car c'est une image locale --}}
                                    <img src="{{ asset('images/placeholder.webp') }}" class="card-img-top custom-card-img" alt="Pas d'image">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title custom-card-title mb-2">{{ $annonce->Titre }}</h5> {{-- Titre personnalisé --}}
                                    <p class="card-text text-muted small mb-1">
                                        <i class="fas fa-tag me-1"></i> Catégorie: <span class="fw-bold">{{ $annonce->categorie->Description ?? 'N/A' }}</span>
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-user-circle me-1"></i> Posté par: <span class="fw-bold">{{ $annonce->user->name ?? 'Utilisateur inconnu' }}</span>
                                    </p>
                                    <p class="card-text flex-grow-1">{{ Str::limit($annonce->DescriptionAbregee, 100, '...') }}</p>

                                    <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="h5 mb-0 custom-price-text fw-bold">{{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit' }}</span>
                                        <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn custom-secondary-button btn-sm">
                                            Voir Détails <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>

                                    <div class="mt-3 text-end">
                                        @auth
                                            {{-- Le bloc conditionnel pour le bouton "Contacter" --}}
                                            @if ($annonce->user && Auth::id() !== $annonce->user->id)
                                                <a href="{{ route('messages.show', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $annonce->user->id]) }}"
                                                   class="btn btn-success btn-sm" title="Contacter le vendeur">
                                                     <i class="fas fa-envelope me-1"></i> Contacter
                                                </a>
                                            @elseif (Auth::id() === $annonce->NoUtilisateur)
                                                {{-- Liens pour l'auteur de l'annonce --}}
                                                <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn btn-outline-warning btn-sm me-1" title="Modifier l'annonce">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')" title="Supprimer l'annonce">
                                                        <i class="fas fa-trash-alt"></i> Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            {{-- Si l'utilisateur n'est pas authentifié, invite à se connecter --}}
                                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm" title="Connectez-vous pour contacter">
                                                <i class="fas fa-sign-in-alt me-1"></i> Connectez-vous pour contacter
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 d-flex justify-content-between align-items-center py-2">
                                    <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> Posté le {{ $annonce->Parution->format('d/m/Y') }}</small>
                                    @if($annonce->DateFin && $annonce->DateFin->isFuture())
                                        <small class="text-info"><i class="fas fa-clock me-1"></i> Expire le {{ $annonce->DateFin->format('d/m/Y') }}</small>
                                    @elseif($annonce->DateFin && $annonce->DateFin->isPast())
                                        <small class="text-danger"><i class="fas fa-ban me-1"></i> Expirée</small>
                                    @else
                                        <small class="text-muted"><i class="fas fa-infinity me-1"></i> Sans expiration</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $annonces->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
