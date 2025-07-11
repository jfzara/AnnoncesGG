@extends('layouts.app')

@section('title', 'Toutes les Annonces - AnnoncesGG')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Toutes les Annonces</h1>

            {{-- Messages Flash --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Bouton pour créer une annonce (visible uniquement si authentifié) --}}
            @auth
                <div class="mb-3">
                    <a href="{{ route('annonces.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Créer une nouvelle annonce
                    </a>
                </div>
            @endauth

            ---

            {{-- Formulaire de recherche et filtre --}}
            <form action="{{ route('annonces.index') }}" method="GET" class="mb-4 p-3 border rounded shadow-sm bg-light">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label for="search" class="visually-hidden">Rechercher</label>
                        <input type="text" name="search" id="search" class="form-control form-control-lg" placeholder="Rechercher par titre ou description..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="category" class="visually-hidden">Catégorie</label>
                        <select name="category" id="category" class="form-select form-select-lg">
                            <option value="all">Toutes les catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->NoCategorie }}" {{ request('category') == $cat->NoCategorie ? 'selected' : '' }}>
                                    {{ $cat->Description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-lg w-100"><i class="fas fa-filter"></i> Filtrer</button>
                    </div>
                    {{-- Bouton pour effacer les filtres si la recherche ou la catégorie est active --}}
                    @if(request('search') || (request('category') && request('category') !== 'all'))
                        <div class="col-12 text-end mt-2">
                            <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times"></i> Effacer les filtres</a>
                        </div>
                    @endif
                </div>
            </form>

            ---

            {{-- Affichage des annonces --}}
            @if ($annonces->isEmpty())
                <div class="alert alert-info text-center" role="alert">
                    <p class="mb-0">Aucune annonce trouvée pour les critères de recherche spécifiés.</p>
                    <p class="mb-0 mt-1">Essayez une autre recherche ou <a href="{{ route('annonces.index') }}">réinitialisez les filtres</a>.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($annonces as $annonce)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0">
                                @if ($annonce->Photo)
                                    <img src="{{ asset('storage/' . $annonce->Photo) }}" class="card-img-top" alt="{{ $annonce->Titre }}" style="height: 200px; object-fit: cover; border-top-left-radius: .25rem; border-top-right-radius: .25rem;">
                                @else
                                    <img src="{{ asset('images/default_annonce.png') }}" class="card-img-top" alt="Pas d'image" style="height: 200px; object-fit: cover; border-top-left-radius: .25rem; border-top-right-radius: .25rem;">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-primary">{{ $annonce->Titre }}</h5>
                                    <p class="card-text text-muted small mb-1">
                                        <i class="fas fa-tag"></i> Catégorie: {{ $annonce->categorie->Description ?? 'N/A' }}
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-user"></i> Posté par: {{ $annonce->user->name ?? 'Utilisateur inconnu' }}
                                    </p>
                                    <p class="card-text">{{ Str::limit($annonce->DescriptionAbregee, 100, '...') }}</p>

                                    <div class="mt-auto d-flex justify-content-between align-items-center pt-2">
                                        <span class="h4 mb-0 text-success fw-bold">{{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit / À discuter' }}</span>
                                        <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn btn-outline-info btn-sm">Voir Détails <i class="fas fa-arrow-right"></i></a>
                                    </div>

                                    <div class="mt-3 text-end">
                                        @auth
                                            {{-- Le bloc conditionnel pour le bouton "Contacter" --}}
                                            @if ($annonce->user && Auth::id() !== $annonce->user->id)
                                                <a href="{{ route('messages.show', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $annonce->user->id]) }}"
                                                   class="btn btn-success btn-sm" title="Contacter le vendeur">
                                                    <i class="fas fa-envelope"></i> Contacter
                                                </a>
                                            @elseif (Auth::id() === $annonce->NoUtilisateur) {{-- NoUtilisateur est l'ID du propriétaire de l'annonce --}}
                                                {{-- Liens pour l'auteur de l'annonce --}}
                                                <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn btn-warning btn-sm me-1" title="Modifier l'annonce">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')" title="Supprimer l'annonce">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            {{-- Si l'utilisateur n'est pas authentifié, invite à se connecter --}}
                                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm" title="Connectez-vous pour contacter">
                                                <i class="fas fa-sign-in-alt"></i> Connectez-vous pour contacter
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 d-flex justify-content-between align-items-center p-3">
                                    <small class="text-muted"><i class="fas fa-calendar-alt"></i> Posté le {{ $annonce->Parution->format('d/m/Y') }}</small>
                                    @if($annonce->DateFin && $annonce->DateFin->isFuture())
                                        <small class="text-danger"><i class="fas fa-clock"></i> Expire le {{ $annonce->DateFin->format('d/m/Y') }}</small>
                                    @elseif($annonce->DateFin && $annonce->DateFin->isPast())
                                        <small class="text-muted"><i class="fas fa-ban"></i> Expirée</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $annonces->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Pour Bootstrap 5, 'data-dismiss' est remplacé par 'data-bs-dismiss' --}}
    {{-- Assurez-vous que Bootstrap JS est correctement inclus dans votre layouts/app.blade.php --}}
@endpush
