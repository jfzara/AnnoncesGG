@extends('layouts.app') {{-- Indique que cette vue étend le layout app.blade.php --}}

@section('title', 'Toutes les Annonces - TrouveTout') {{-- Définit le titre spécifique de cette page --}}

@section('content') {{-- C'est ici que le contenu spécifique de cette page va être injecté dans le layout --}}

    <div class="container my-5"> {{-- Ajout de marges globales --}}
        <h1 class="custom-heading text-center mb-5"><i class="fas fa-search me-3"></i> {{ __('Toutes les Annonces') }}</h1>

        {{-- Messages de succès/erreur --}}
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

        {{-- Bouton "Créer une annonce" visible si l'utilisateur est connecté --}}
        @auth
            <div class="text-end mb-4">
                <a href="{{ route('annonces.create') }}" class="btn custom-primary-button btn-lg">
                    <i class="fas fa-plus-circle me-2"></i> {{ __('Créer une nouvelle annonce') }}
                </a>
            </div>
        @endauth

        {{-- Votre section de recherche et pagination --}}
        <div class="card shadow-sm mb-4"> {{-- Encapsulation dans une carte avec ombre --}}
            <div class="card-header custom-gradient-header text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-filter me-2"></i> {{ __('Filtres et tri') }}</h4>
            </div>
            <div class="card-body">
                <form id="frmRecherche" class="row g-3 align-items-end" method="GET" action="{{ route('annonces.index') }}"> {{-- Utilisation de row g-3 pour l'espacement --}}
                    <input id="NbParPageInput" name="NbParPage" type="hidden" value="{{ request('NbParPage', 10) }}">
                    <input id="PageInput" name="Page" type="hidden" value="{{ request('Page', 1) }}">

                    {{-- Éléments par page et nombre d'annonces --}}
                    <div class="col-md-auto d-flex align-items-center">
                        <label for="ddlNbParPage" class="form-label mb-0 me-2">{{ __('Afficher') }} :</label>
                        <select id="ddlNbParPage" class="form-select form-select-sm custom-select-filter">
                            <option value="5" {{ request('NbParPage') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('NbParPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('NbParPage') == 15 ? 'selected' : '' }}>15</option>
                            <option value="20" {{ request('NbParPage') == 20 ? 'selected' : '' }}>20</option>
                        </select>
                        <p class="text-muted ms-3 mb-0">
                            @if ($annonces->total() === 0)
                                {{ __('Aucune annonce trouvée.') }}
                            @elseif ($annonces->total() === 1)
                                {{ __('1 annonce trouvée.') }}
                            @else
                                {{ $annonces->total() }} {{ __('annonces trouvées.') }}
                            @endif
                        </p>
                    </div>

                    {{-- Ordre et direction --}}
                    <div class="col-md-auto d-flex align-items-center ms-auto"> {{-- ms-auto pour pousser à droite --}}
                        <label for="TypeOrdre" class="form-label mb-0 me-2">{{ __('Trier par') }} :</label>
                        <select class="form-select form-select-sm custom-select-filter me-2" id="TypeOrdre" name="TypeOrdre">
                            <option value="Parution" {{ request('TypeOrdre') == 'Parution' ? 'selected' : '' }}>{{ __('Date') }}</option>
                            <option value="NoUtilisateur" {{ request('TypeOrdre') == 'NoUtilisateur' ? 'selected' : '' }}>{{ __('Auteur') }}</option>
                            <option value="Categorie" {{ request('TypeOrdre') == 'Categorie' ? 'selected' : '' }}>{{ __('Catégorie') }}</option>
                            <option value="Prix" {{ request('TypeOrdre') == 'Prix' ? 'selected' : '' }}>{{ __('Prix') }}</option> {{-- Ajout du tri par prix --}}
                        </select>
                        <select class="form-select form-select-sm custom-select-filter" id="Ordre" name="Ordre">
                            <option value="ASC" {{ request('Ordre') == 'ASC' ? 'selected' : '' }}>▲ {{ __('Croissant') }}</option>
                            <option value="DESC" {{ request('Ordre') == 'DESC' ? 'selected' : '' }}>▼ {{ __('Décroissant') }}</option>
                        </select>
                    </div>

                    {{-- Champ de recherche simple --}}
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <input class="form-control custom-form-control-sm" type="text" value="{{ request('Description') }}" id="Description" name="Description" placeholder="{{ __('Rechercher par titre ou description...') }}">
                            <button type="submit" class="btn custom-primary-button btn-sm"><i class="fas fa-search"></i></button>
                            <button id="btnAfficherAvance" type="button" class="btn custom-secondary-button btn-sm">{{ __('Plus de filtres') }} <i class="fas fa-chevron-down ms-1"></i></button>
                        </div>
                    </div>

                    {{-- Recherche avancée (initiallement cachée) --}}
                    <div id="divRechercheAvance" class="col-12 mt-3 p-3 border rounded bg-light-subtle" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="Auteur" class="form-label custom-label-auth">{{ __('Auteur') }} :</label>
                                <input class="form-control custom-form-control-sm" type="text" id="Auteur" name="Auteur" value="{{ request('Auteur') }}" placeholder="{{ __('Nom de l\'auteur') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="CategorieSelect" class="form-label custom-label-auth">{{ __('Catégorie') }} :</label>
                                <select class="form-select custom-form-control-sm" id="CategorieSelect" name="Categorie">
                                    <option value="">{{ __('Toutes les catégories') }}</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie->NoCategorie }}" {{ request('Categorie') == $categorie->NoCategorie ? 'selected' : '' }}>{{ $categorie->Description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label custom-label-auth">{{ __('Fourchette de Prix') }} :</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" class="form-control custom-form-control-sm" id="PrixMin" name="PrixMin" value="{{ request('PrixMin') }}" placeholder="{{ __('Min.') }}">
                                    <span class="input-group-text">-</span>
                                    <input type="number" step="0.01" class="form-control custom-form-control-sm" id="PrixMax" name="PrixMax" value="{{ request('PrixMax') }}" placeholder="{{ __('Max.') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="DateDebut" class="form-label custom-label-auth">{{ __('Date de publication (début)') }} :</label>
                                <input class="form-control custom-form-control-sm" type="date" id="DateDebut" name="DateDebut" value="{{ request('DateDebut') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="DateFin" class="form-label custom-label-auth">{{ __('Date de publication (fin)') }} :</label>
                                <input class="form-control custom-form-control-sm" type="date" id="DateFin" name="DateFin" value="{{ request('DateFin') }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn custom-primary-button btn-sm"><i class="fas fa-check-circle me-1"></i> {{ __('Appliquer les filtres') }}</button>
                            <button type="button" class="btn custom-clear-filters-button btn-sm ms-2" id="btnClearFilters"><i class="fas fa-times-circle me-1"></i> {{ __('Effacer les filtres') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <hr class="my-4">

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="divListe"> {{-- Utilisation de row-cols pour une grille responsive --}}
            @if ($annonces->isEmpty())
                <div class="col-12">
                    <div class="alert alert-warning text-center p-4 shadow-sm" role="alert">
                        <h4 class="alert-heading mb-3"><i class="fas fa-exclamation-triangle me-2"></i> {{ __('Aucune annonce ne correspond à votre recherche.') }}</h4>
                        <p class="mb-0">{{ __('Essayez d\'ajuster vos filtres ou de réinitialiser la recherche.') }}</p>
                        <button type="button" class="btn custom-clear-filters-button mt-3" id="btnClearFiltersLarge"><i class="fas fa-redo me-2"></i> {{ __('Réinitialiser tous les filtres') }}</button>
                    </div>
                </div>
            @else
                @foreach ($annonces as $annonce)
                    <div class="col">
                        <div class="card h-100 shadow-sm custom-card-hover"> {{-- Carte avec ombre et effet hover --}}
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 border-bottom">
                                <small class="text-muted fw-bold">#{{ $annonce->NoAnnonce }}</small>
                                <span class="badge custom-badge-category">{{ $annonce->categorie->Description ?? 'N/A' }}</span> {{-- Badge de catégorie stylisé --}}
                            </div>
                            <div class="custom-card-img-container"> {{-- Conteneur d'image stylisé --}}
                                @if ($annonce->Photo)
                                    <img src="{{ asset('storage/' . $annonce->Photo) }}" alt="{{ $annonce->DescriptionAbregee }}" class="custom-card-img">
                                @else
                                    <img src="{{ asset('images/placeholder.png') }}" alt="{{ __('Pas d\'image') }}" class="custom-card-img">
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <h5 class="card-title custom-card-title mb-2 flex-grow-0"><a href="{{ route('annonces.show', $annonce->NoAnnonce) }}">{{ $annonce->Titre }}</a></h5> {{-- Utilisation de Titre ici --}}
                                <p class="card-text text-muted small flex-grow-1 overflow-hidden" style="max-height: 3em;">{{ Str::limit($annonce->DescriptionComplete, 70) }}</p> {{-- Description plus courte --}}
                                <div class="d-flex justify-content-between align-items-end mt-auto pt-2">
                                    <div>
                                        <small class="text-primary fw-semibold">{{ $annonce->user->name ?? 'Utilisateur inconnu' }}</small>
                                        <br>
                                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($annonce->Parution)->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="custom-price-text">
                                        {{ number_format($annonce->Prix, 2, ',', ' ') }} {{ $annonce->Prix > 0 ? '$' : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end align-items-center py-2">
                                {{-- Boutons d'action --}}
                                @auth
                                    @if (Auth::id() !== $annonce->NoUtilisateur)
                                        <a href="{{ route('annonces.contact.create', $annonce->NoAnnonce) }}" class="btn custom-primary-button btn-sm ms-2" title="{{ __('Contacter l\'auteur') }}">
                                            <i class="fas fa-envelope"></i> {{ __('Contacter') }}
                                        </a>
                                    @else
                                        <span class="badge bg-secondary ms-2 p-2"><i class="fas fa-user-tag me-1"></i> {{ __('Votre annonce') }}</span>
                                        <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn custom-edit-button btn-sm ms-2" title="{{ __('Modifier') }}"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn custom-delete-button btn-sm ms-1" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette annonce ?') }}');" title="{{ __('Supprimer') }}"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn custom-primary-button btn-sm ms-2" title="{{ __('Connectez-vous pour contacter') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Contacter') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Liens de pagination --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $annonces->appends(request()->query())->links('pagination::bootstrap-5') }} {{-- S'assurer que les paramètres de recherche sont maintenus --}}
        </div>

    </div> {{-- Fin du container --}}

@endsection {{-- Fin de la section 'content' --}}

@section('scripts') {{-- Vous pouvez placer les scripts spécifiques à cette page ici --}}
    <script>
        let booAfficherAvance = false; // Variable pour suivre l'état de la recherche avancée

        // Fonction pour changer les paramètres d'URL et soumettre le formulaire
        function changerParam(strParam, strValeur) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set(strParam, strValeur);
            // Réinitialiser la page à 1 si on change le nombre par page ou un filtre
            if (strParam !== 'Page') {
                urlParams.set('Page', 1);
            }
            // Mettre à jour les champs cachés avant de soumettre
            document.getElementById('NbParPageInput').value = urlParams.get('NbParPage') || '10';
            document.getElementById('PageInput').value = urlParams.get('Page') || '1';

            document.getElementById('frmRecherche').submit();
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Initialiser les valeurs cachées avec les valeurs actuelles de l'URL
            const urlParams = new URLSearchParams(window.location.search);
            document.getElementById('NbParPageInput').value = urlParams.get('NbParPage') || '10';
            document.getElementById('PageInput').value = urlParams.get('page') || '1'; // 'page' est le param par défaut de Laravel

            // Gérer le changement du nombre d'éléments par page
            document.getElementById('ddlNbParPage').addEventListener('change', function () {
                changerParam('NbParPage', this.value);
            });

            // Gérer l'affichage/masquage de la recherche avancée
            const btnAfficherAvance = document.getElementById('btnAfficherAvance');
            const divRechercheAvance = document.getElementById('divRechercheAvance');

            btnAfficherAvance.addEventListener('click', function () {
                booAfficherAvance = !booAfficherAvance;
                if (booAfficherAvance) {
                    divRechercheAvance.style.display = 'block';
                    btnAfficherAvance.innerHTML = `{{ __('Moins de filtres') }} <i class="fas fa-chevron-up ms-1"></i>`;
                } else {
                    divRechercheAvance.style.display = 'none';
                    btnAfficherAvance.innerHTML = `{{ __('Plus de filtres') }} <i class="fas fa-chevron-down ms-1"></i>`;
                }
            });

            // Gérer le bouton "Effacer les filtres" (pour la recherche avancée)
            const btnClearFilters = document.getElementById('btnClearFilters');
            if (btnClearFilters) {
                btnClearFilters.addEventListener('click', function() {
                    document.getElementById('Auteur').value = '';
                    document.getElementById('CategorieSelect').value = '';
                    document.getElementById('PrixMin').value = '';
                    document.getElementById('PrixMax').value = '';
                    document.getElementById('DateDebut').value = '';
                    document.getElementById('DateFin').value = '';
                    // Optionnel: Soumettre le formulaire pour appliquer l'effacement
                    document.getElementById('frmRecherche').submit();
                });
            }

            // Gérer le bouton "Réinitialiser tous les filtres" (pour le message "Aucune annonce")
            const btnClearFiltersLarge = document.getElementById('btnClearFiltersLarge');
            if (btnClearFiltersLarge) {
                btnClearFiltersLarge.addEventListener('click', function() {
                    // Réinitialiser tous les champs de recherche
                    document.getElementById('Description').value = '';
                    document.getElementById('TypeOrdre').value = 'Parution';
                    document.getElementById('Ordre').value = 'DESC';
                    document.getElementById('Auteur').value = '';
                    document.getElementById('CategorieSelect').value = '';
                    document.getElementById('PrixMin').value = '';
                    document.getElementById('PrixMax').value = '';
                    document.getElementById('DateDebut').value = '';
                    document.getElementById('DateFin').value = '';
                    // Réinitialiser le nombre d'éléments par page et la page
                    changerParam('NbParPage', '10'); // Ou votre valeur par défaut
                    changerParam('Page', '1');
                    // Soumettre le formulaire pour appliquer l'effacement
                    document.getElementById('frmRecherche').submit();
                });
            }

            // Initialiser l'état de la recherche avancée au chargement de la page si des filtres sont appliqués
            if (urlParams.has('Auteur') || urlParams.has('Categorie') || urlParams.has('DateDebut') || urlParams.has('DateFin') || urlParams.has('PrixMin') || urlParams.has('PrixMax')) {
                booAfficherAvance = true;
                btnAfficherAvance.innerHTML = `{{ __('Moins de filtres') }} <i class="fas fa-chevron-up ms-1"></i>`;
                divRechercheAvance.style.display = 'block'; // Utiliser 'block' pour le display CSS
            } else {
                divRechercheAvance.style.display = 'none';
            }

            // Mettre à jour le texte du bouton de recherche avancée si les filtres avancés sont actifs
            if (booAfficherAvance) {
                btnAfficherAvance.innerHTML = `{{ __('Moins de filtres') }} <i class="fas fa-chevron-up ms-1"></i>`;
            } else {
                btnAfficherAvance.innerHTML = `{{ __('Plus de filtres') }} <i class="fas fa-chevron-down ms-1"></i>`;
            }
        });
    </script>
@endsection {{-- Fin de la section 'scripts' --}}
