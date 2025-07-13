@extends('layouts.app')

@section('title', 'Mes Annonces - TrouveTout') {{-- Titre plus spécifique --}}

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0"> {{-- Ajout d'une ombre et suppression de la bordure --}}
                <div class="card-header custom-gradient-header"> {{-- Utilisation du dégradé personnalisé --}}
                    <i class="fas fa-list-alt fa-lg me-3"></i> {{-- Icône pour la gestion des annonces --}}
                    <h3 class="mb-0 text-white">Mes Annonces</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center" role="alert"> {{-- Centrer l'icône avec le texte --}}
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center" role="alert"> {{-- Centrer l'icône avec le texte --}}
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    {{-- Bouton pour créer une nouvelle annonce, toujours visible --}}
                    <div class="d-flex justify-content-end mb-4">
                        <a href="{{ route('annonces.create') }}" class="btn custom-primary-button">
                            <i class="fas fa-plus-circle me-2"></i> Créer une nouvelle annonce
                        </a>
                    </div>

                    @forelse ($annonces as $annonce)
                        <div class="card mb-3 shadow-sm border-0"> {{-- Chaque annonce est une carte avec ombre --}}
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    {{-- Image de l'annonce (si présente) --}}
                                    @if($annonce->Photo)
                                        <img src="{{ asset('storage/' . $annonce->Photo) }}" alt="{{ $annonce->Titre }}" class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('images/placeholder.webp') }}" alt="Pas de photo" class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <h5 class="card-title mb-1">
                                            <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="text-decoration-none text-dark fw-bold">
                                                {{ $annonce->Titre }}
                                            </a>
                                        </h5>
                                        <p class="card-text text-muted mb-1">{{ $annonce->DescriptionAbregee }}</p>
                                        <p class="card-text">
                                            <span class="badge bg-secondary me-2"><i class="fas fa-tag me-1"></i>{{ $annonce->categorie->Description }}</span>
                                            <span class="badge bg-info text-dark"><i class="fas fa-money-bill-wave me-1"></i>{{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit' }}</span>
                                        </p>
                                        <p class="card-text mb-0">
                                            <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i>Publié le: {{ $annonce->Parution->format('d/m/Y') }}</small>
                                        </p>
                                        @if($annonce->DateFin)
                                            <p class="card-text mb-0">
                                                <small class="text-muted">
                                                    <i class="fas fa-hourglass-end me-1"></i>Expire le: {{ $annonce->DateFin->format('d/m/Y') }}
                                                </small>
                                                @if($annonce->DateFin->isPast())
                                                    <span class="badge bg-danger ms-2"><i class="fas fa-exclamation-circle me-1"></i>Expirée</span>
                                                @endif
                                            </p>
                                        @else
                                            <p class="card-text mb-0"><small class="text-muted"><i class="fas fa-infinity me-1"></i>Pas de date d'expiration</small></p>
                                        @endif
                                    </div>
                                </div>


                                <div class="mt-3 border-top pt-3 d-flex justify-content-end gap-2"> {{-- Ajout de gap-2 pour espacement des boutons --}}
                                    <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye me-1"></i> Voir détails
                                    </a>
                                    <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i> Modifier
                                    </a>

                                    <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                            <i class="fas fa-trash-alt me-1"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                            <h4 class="alert-heading">Aucune annonce trouvée</h4>
                            <p>Vous n'avez pas encore d'annonces actives. Créez-en une dès maintenant !</p>
                            <hr>
                            <a href="{{ route('annonces.create') }}" class="btn custom-primary-button">
                                <i class="fas fa-plus-circle me-2"></i> Créer ma première annonce
                            </a>
                        </div>
                    @endforelse

                    <div class="mt-4">
                        {{ $annonces->links() }} {{-- Pour la pagination --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsectio
