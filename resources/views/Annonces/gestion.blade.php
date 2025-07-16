@extends('layouts.app')

@section('title', 'MES ANNONCES - TROUVETOUT') {{-- Titre en capitales --}}

@section('content')
<div class="container mt-4 mb-5"> {{-- Ajout de mb-5 pour un espace généreux en bas --}}
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0"> {{-- Suppression de shadow-sm et border-0 pour utiliser nos styles --}}
                <div class="card-header custom-gradient-header d-flex align-items-center"> {{-- Assure l'alignement de l'icône et du texte --}}
                    <i class="fas fa-list-alt fa-lg me-3"></i> {{-- Icône pour la gestion des annonces --}}
                    <h3 class="mb-0 text-white">MES ANNONCES</h3> {{-- Titre en capitales, couleur définie par custom-gradient-header --}}
                </div>
                <div class="card-body p-4"> {{-- Augmentation du padding interne pour plus d'aération --}}
                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    {{-- Bouton pour créer une nouvelle annonce --}}
                    <div class="d-flex justify-content-end mb-4"> {{-- Espacement généreux --}}
                        <a href="{{ route('annonces.create') }}" class="btn custom-primary-button">
                            <i class="fas fa-plus-circle me-2"></i> CRÉER UNE NOUVELLE ANNONCE {{-- Texte en capitales --}}
                        </a>
                    </div>

                    @forelse ($annonces as $annonce)
                        <div class="card mb-4 border-0"> {{-- Chaque annonce est une carte. Augmentation de mb, suppression de shadow-sm et border-0 --}}
                            <div class="card-body p-4"> {{-- Augmentation du padding --}}
                                <div class="d-flex align-items-center mb-4"> {{-- Espacement pour le bloc image/texte --}}
                                    {{-- Image de l'annonce (si présente) --}}
                                    @if($annonce->Photo)
                                        <img src="{{ asset('storage/' . $annonce->Photo) }}" alt="{{ $annonce->Titre }}" class="img-thumbnail me-4" style="width: 120px; height: 120px; object-fit: cover; border-radius: 0; border: 2px solid var(--border-subtle);"> {{-- Image plus grande, sans arrondi, avec bordure subtile --}}
                                    @else
                                        <img src="{{ asset('images/placeholder.webp') }}" alt="Pas de photo" class="img-thumbnail me-4" style="width: 120px; height: 120px; object-fit: cover; border-radius: 0; border: 2px solid var(--border-subtle);"> {{-- Image plus grande, sans arrondi, avec bordure subtile --}}
                                    @endif
                                    <div class="flex-grow-1"> {{-- Permet au texte de prendre l'espace restant --}}
                                        <h5 class="card-title mb-2"> {{-- Plus d'espace sous le titre --}}
                                            <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="text-decoration-none fw-bold" style="color: var(--primary-dark);"> {{-- Couleur du texte du titre principale --}}
                                                {{ Str::upper($annonce->Titre) }} {{-- Titre en capitales --}}
                                            </a>
                                        </h5>
                                        <p class="card-text text-muted mb-2">{{ Str::limit($annonce->DescriptionAbregee, 100) }}</p> {{-- Réduction de la description pour l'aération --}}
                                        <p class="card-text mb-1">
                                            <span class="badge bg-secondary me-2 p-2" style="border-radius: 0; font-size: 0.9em; text-transform: uppercase;"><i class="fas fa-tag me-1"></i>{{ Str::upper($annonce->categorie->Description) }}</span> {{-- Badges plus grands, sans arrondi, texte en capitales --}}
                                            <span class="badge bg-info text-dark p-2" style="border-radius: 0; font-size: 0.9em; text-transform: uppercase;"><i class="fas fa-money-bill-wave me-1"></i>{{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'GRATUIT' }}</span> {{-- Texte en capitales --}}
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
                                                    <span class="badge bg-danger ms-2 p-2" style="border-radius: 0; text-transform: uppercase;"><i class="fas fa-exclamation-circle me-1"></i>Expirée</span> {{-- Badge sans arrondi, texte en capitales --}}
                                                @endif
                                            </p>
                                        @else
                                            <p class="card-text mb-0"><small class="text-muted"><i class="fas fa-infinity me-1"></i>PAS DE DATE D'EXPIRATION</small></p> {{-- Texte en capitales --}}
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-top d-flex justify-content-end gap-3"> {{-- Plus d'espace au-dessus des boutons, gap augmenté --}}
                                    <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn btn-outline-secondary btn-sm p-2" style="border-radius: 0; text-transform: uppercase; font-weight: 700;"> {{-- Boutons sans arrondi, texte en capitales --}}
                                        <i class="fas fa-eye me-1"></i> VOIR DÉTAILS
                                    </a>
                                    <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn btn-outline-secondary btn-sm p-2" style="border-radius: 0; text-transform: uppercase; font-weight: 700;">
                                        <i class="fas fa-edit me-1"></i> MODIFIER
                                    </a>

                                    <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm p-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')" style="border-radius: 0; text-transform: uppercase; font-weight: 700;"> {{-- Bouton supprimer aussi en capitales --}}
                                            <i class="fas fa-trash-alt me-1"></i> SUPPRIMER
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center py-5" style="border-radius: 0; border: 2px solid var(--border-subtle);"> {{-- Alertes sans arrondi, plus de padding, avec bordure --}}
                            <i class="fas fa-info-circle fa-3x mb-4 d-block" style="color: var(--info-color);"></i> {{-- Icône plus grande et couleur accentuée --}}
                            <h4 class="alert-heading text-uppercase mb-3" style="font-family: var(--font-heading);">AUCUNE ANNONCE TROUVÉE</h4> {{-- Titre en capitales, police titre --}}
                            <p class="mb-4">VOUS N'AVEZ PAS ENCORE D'ANNONCES ACTIVES. CRÉEZ-EN UNE DÈS MAINTENANT !</p> {{-- Texte en capitales --}}
                            <hr style="border-top: 1px solid var(--border-subtle);"> {{-- Ligne de séparation plus subtile --}}
                            <a href="{{ route('annonces.create') }}" class="btn custom-primary-button mt-4">
                                <i class="fas fa-plus-circle me-2"></i> CRÉER MA PREMIÈRE ANNONCE {{-- Texte en capitales --}}
                            </a>
                        </div>
                    @endforelse

                    <div class="mt-4">
                        {{ $annonces->links() }} {{-- Pour la pagination (les styles seront gérés par Bootstrap lui-même et app.blade.php) --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styles spécifiques pour cette page si non couverts par app.blade.php ou pour les surcharges */

    .card {
        border: 2px solid var(--border-strong) !important; /* Bordure forte pour toutes les cartes */
        border-radius: 0 !important; /* Pas d'arrondis */
        box-shadow: var(--shadow-none) !important; /* Pas d'ombre */
        background-color: var(--light-pure-white); /* Fond blanc pur */
    }

    .card-header.custom-gradient-header {
        background: var(--primary-dark) !important; /* Utilise la couleur unie de notre palette */
        border-radius: 0 !important;
        border-bottom: 2px solid var(--accent-red-orange) !important; /* Bordure accentuée forte */
        color: var(--text-on-dark) !important; /* Texte blanc sur fond sombre */
        padding: 1.8rem 2.2rem !important; /* Plus de padding pour l'aération */
        display: flex;
        align-items: center;
    }

    .card-header.custom-gradient-header h3 {
        margin-bottom: 0 !important;
        color: inherit !important; /* Hérite de la couleur du parent */
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
    }

    .custom-primary-button {
        /* Déjà stylisé dans app.blade.php, juste pour s'assurer des overrides si besoin */
        border-radius: 0 !important;
        text-transform: uppercase !important;
        font-weight: 700 !important;
        letter-spacing: 0.05em !important;
    }

    .img-thumbnail {
        border-radius: 0 !important;
        border: 2px solid var(--border-subtle) !important;
        padding: 0; /* Supprime le padding par défaut de img-thumbnail */
    }

    .badge {
        border-radius: 0 !important; /* Tous les badges sans arrondi */
        font-weight: 700 !important; /* Gras pour l'impact */
        padding: 0.5em 0.8em !important; /* Plus de padding */
        text-transform: uppercase !important; /* Texte en capitales */
        letter-spacing: 0.03em;
    }

    /* Couleurs spécifiques pour les badges d'état si non couvertes par Bootstrap par défaut */
    .badge.bg-secondary { background-color: var(--secondary-dark) !important; color: var(--text-on-dark) !important; }
    .badge.bg-info { background-color: var(--info-color) !important; color: var(--text-on-dark) !important; } /* S'assurer que le texte est lisible sur info */
    .badge.bg-danger { background-color: var(--danger-color) !important; color: var(--text-on-dark) !important; }

    .btn-outline-secondary {
        border: 2px solid var(--secondary-dark) !important;
        color: var(--secondary-dark) !important;
        font-weight: 700 !important;
        padding: 0.85rem 1.8rem !important;
        border-radius: 0 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
    }
    .btn-outline-secondary:hover {
        background-color: var(--secondary-dark) !important;
        color: var(--text-on-dark) !important;
    }
    .btn-outline-info { /* Pour "Voir détails" */
        border: 2px solid var(--info-color) !important;
        color: var(--info-color) !important;
        font-weight: 700 !important;
        padding: 0.85rem 1.8rem !important;
        border-radius: 0 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
    }
    .btn-outline-info:hover {
        background-color: var(--info-color) !important;
        color: var(--text-on-dark) !important;
    }
    .btn-outline-warning { /* Pour "Modifier" */
        border: 2px solid var(--warning-color) !important;
        color: var(--warning-color) !important;
        font-weight: 700 !important;
        padding: 0.85rem 1.8rem !important;
        border-radius: 0 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
    }
    .btn-outline-warning:hover {
        background-color: var(--warning-color) !important;
        color: var(--text-on-light) !important; /* Texte sombre sur jaune */
    }
    .btn-outline-danger { /* Pour "Supprimer" */
        border: 2px solid var(--danger-color) !important;
        color: var(--danger-color) !important;
        font-weight: 700 !important;
        padding: 0.85rem 1.8rem !important;
        border-radius: 0 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
    }
    .btn-outline-danger:hover {
        background-color: var(--danger-color) !important;
        color: var(--text-on-dark) !important;
    }


    /* Alertes (messages de session) */
    .alert {
        border-radius: 0 !important; /* Sans arrondi */
        border: 2px solid !important; /* Bordure forte */
        font-weight: 500;
        padding: 1.5rem !important; /* Plus de padding */
        margin-bottom: 2rem !important; /* Plus d'espace en dessous */
        text-transform: uppercase; /* Texte en capitales */
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
        font-family: var(--font-heading) !important; /* Utilise la police des titres */
        color: inherit !important; /* Hérite la couleur de l'alerte */
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
    }


    /* Pagination */
    .pagination {
        --bs-pagination-color: var(--primary-dark);
        --bs-pagination-hover-color: var(--accent-red-orange);
        --bs-pagination-focus-color: var(--accent-red-orange);
        --bs-pagination-active-bg: var(--accent-red-orange);
        --bs-pagination-active-border-color: var(--accent-red-orange);
        --bs-pagination-disabled-color: var(--secondary-dark);
        --bs-pagination-disabled-bg: var(--border-subtle);
        --bs-pagination-border-radius: 0; /* Pas d'arrondis */
        --bs-pagination-border-width: 2px; /* Bordures plus épaisses */
        --bs-pagination-border-color: var(--border-strong);
    }
    .page-item .page-link {
        border-radius: 0 !important;
        padding: 0.75rem 1.25rem !important; /* Plus de padding pour les liens de pagination */
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        transition: all 0.1s ease-in-out;
    }
    .page-item.active .page-link {
        background-color: var(--accent-red-orange) !important;
        border-color: var(--accent-red-orange) !important;
        color: var(--text-on-dark) !important;
    }
    .page-item:not(.active) .page-link:hover {
        background-color: var(--secondary-dark) !important;
        color: var(--accent-red-orange) !important;
        border-color: var(--secondary-dark) !important;
    }
    .page-item.disabled .page-link {
        color: var(--secondary-dark) !important;
        background-color: var(--border-subtle) !important;
        border-color: var(--border-strong) !important;
    }

</style>
@endsection
