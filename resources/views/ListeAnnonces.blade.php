@extends('layouts.app') {{-- Indique que cette vue étend le layout app.blade.php --}}

@section('title', 'Toutes les Annonces') {{-- Définit le titre spécifique de cette page --}}

@section('content') {{-- C'est ici que le contenu spécifique de cette page va être injecté dans le layout --}}

    <h1 class="mb-4">Toutes les Annonces</h1>

    {{-- Message de succès/erreur --}}
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

    {{-- Bouton "Créer une annonce" visible si l'utilisateur est connecté --}}
    @auth
        <div class="text-end mb-3">
            <a href="{{ route('annonces.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Créer une nouvelle annonce</a>
        </div>
    @endauth

    {{-- Votre section de recherche et pagination --}}
    <div id="divPanel" class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div id="divNbParPage" class="mb-2 mb-md-0 d-flex align-items-center">
            <label class="col-form-label me-2 mb-0">Éléments par page : </label>
            <select id="ddlNbParPage" class="form-control form-control-sm" style="width: auto;">
                <option value="5" {{ request('NbParPage') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('NbParPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ request('NbParPage') == 15 ? 'selected' : '' }}>15</option>
                <option value="20" {{ request('NbParPage') == 20 ? 'selected' : '' }}>20</option>
            </select>
           <h5 class="text-secondary font-italic ms-3 mb-0">
    @if ($annonces->total() === 0)
        Aucune annonce trouvée.
    @elseif ($annonces->total() === 1)
        1 annonce trouvée.
    @else
        {{ $annonces->total() }} annonces trouvées.
    @endif
</h5>
        </div>
        <div id="divRecherche" class="flex-fill d-flex justify-content-end">
            <form id="frmRecherche" class="d-flex flex-column" method="GET" action="{{ route('annonces.index') }}">
                <input id="NbParPageInput" name="NbParPage" type="hidden" value="{{ request('NbParPage', 10) }}">
                <input id="PageInput" name="Page" type="hidden" value="{{ request('Page', 1) }}">

                <div id="divRechercheSimple" class="d-flex align-items-center">
                    <div class="form-group d-inline-flex my-0 me-2">
                        <label class="col-form-label me-1">Ordre : </label>
                        <div class="my-auto me-1">
                            <select class="form-control form-control-sm" id="TypeOrdre" name="TypeOrdre">
                                <option value="Parution" {{ request('TypeOrdre') == 'Parution' ? 'selected' : '' }}>Date</option>
                                <option value="NoUtilisateur" {{ request('TypeOrdre') == 'NoUtilisateur' ? 'selected' : '' }}>Auteur</option>
                                <option value="Categorie" {{ request('TypeOrdre') == 'Categorie' ? 'selected' : '' }}>Catégorie</option>
                            </select>
                        </div>
                        <div class="m-auto">
                            <select class="form-control form-control-sm" id="Ordre" name="Ordre">
                                <option value="ASC" {{ request('Ordre') == 'ASC' ? 'selected' : '' }}>▲</option>
                                <option value="DESC" {{ request('Ordre') == 'DESC' ? 'selected' : '' }}>▼</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group d-inline-flex mx-2 my-0">
                        <div class="m-auto mx-1">
                            <input class="form-control form-control-sm" type="text" value="{{ request('Description') }}" id="Description" name="Description" placeholder="Rechercher par description...">
                        </div>
                    </div>

                    <input class="btn btn-primary form-control-sm m-auto me-2" type="submit" value="Rechercher">
                    <button id="btnAfficherAvance" type="button" class="btn btn-secondary font-weight-bold form-control-sm">+</button>
                </div>

                <div id="divRechercheAvancé" class="col-12 mt-2 border pt-2 pr-5" style="display: none;">
                    <div class="form-group row mb-2">
                        <label class="col-3 col-form-label">Auteur :</label>
                        <div class="col-9">
                            <input class="form-control form-control-sm" type="text" id="Auteur" name="Auteur" value="{{ request('Auteur') }}" placeholder="Nom de l'auteur">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-3 col-form-label">Catégorie :</label>
                        <div class="col-9">
                            <select class="form-control form-control-sm" id="CategorieSelect" name="Categorie">
                                <option value="">Toutes</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->NoCategorie }}" {{ request('Categorie') == $categorie->NoCategorie ? 'selected' : '' }}>{{ $categorie->Description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-3 col-form-label">Date :</label>
                        <div class="col-4">
                            <input class="form-control form-control-sm" type="date" id="DateDebut" name="DateDebut" value="{{ request('DateDebut') }}">
                        </div>
                        <p class="col-1 p-0 m-auto text-center">à</p>
                        <div class="col-4">
                            <input class="form-control form-control-sm" type="date" id="DateFin" name="DateFin" value="{{ request('DateFin') }}">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-sm">Appliquer les filtres</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <div id="divListe" class="d-flex flex-wrap justify-content-around mt-2">
        @if ($annonces->isEmpty())
            <div class="alert alert-warning w-100" role="alert">
                Aucune annonce ne correspond à votre recherche.
            </div>
        @else
            @foreach ($annonces as $annonce)
                <div class="m-3">
                    <div class="card annonce" style="width: 300px; height: 450px;">
                        <div class="card-header d-flex justify-content-between py-1">
                            <div class="text-left">#{{ $annonce->NoAnnonce }}</div>
                            <div class="text-right">{{ $annonce->categorie->Description ?? 'N/A' }}</div>
                        </div>
                        <div class="overflow-hidden text-center imageSize" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                            @if ($annonce->Photo)
                                <img src="{{ asset('storage/' . $annonce->Photo) }}" alt="{{ $annonce->DescriptionAbregee }}" class="img-fluid" style="max-height: 100%; width: auto;">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}" alt="Pas d'image" class="img-fluid" style="max-height: 100%; width: auto;">
                            @endif
                        </div>
                        <div class="card-body pb-1 d-flex flex-column">
                            <h6 class="card-title flex-grow-0"><a href="{{ route('annonces.show', $annonce->NoAnnonce) }}">{{ $annonce->DescriptionAbregee }}</a></h6>
                            <p class="card-text text-muted flex-grow-1" style="font-size: 0.85em;">{{ Str::limit($annonce->DescriptionComplete, 50) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="text-left">
                                    <a href="mailto:{{ $annonce->user->email ?? '#' }}">{{ $annonce->user->name ?? 'Utilisateur inconnu' }}</a>
                                </div>
                                <div class="text-right font-weight-bold">
                                    <span>{{ number_format($annonce->Prix, 2, ',', ' ') }} {{ $annonce->Prix > 0 ? '$' : '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center py-0">
                            <div class="text-left" style="font-size: 0.75em;">{{ \Carbon\Carbon::parse($annonce->Parution)->format('Y-m-d H:i') }}</div>
                            <div class="text-right d-flex">
                                @auth
                                    @if (Auth::id() === $annonce->NoUtilisateur)
                                        <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn btn-warning btn-sm ms-1" title="Modifier"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm ms-1" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Liens de pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $annonces->links() }}
    </div>

@endsection {{-- Fin de la section 'content' --}}

@section('scripts') {{-- Vous pouvez placer les scripts spécifiques à cette page ici --}}
    <script>
        let booAfficherAvance = false;

        function changerParam(strParam, strValeur) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set(strParam, strValeur);

            // Mettre à jour les champs cachés avant de soumettre
            document.getElementById(strParam + 'Input').value = strValeur;

            // Soumettre le formulaire de recherche pour appliquer les filtres et la pagination
            document.getElementById('frmRecherche').submit();
        }

        $(document).ready(() => {
            // Initialiser les valeurs cachées avec les valeurs actuelles de l'URL
            $('#NbParPageInput').val(new URLSearchParams(window.location.search).get('NbParPage') || '10');
            $('#PageInput').val(new URLSearchParams(window.location.search).get('page') || '1'); // <-- 'page' est le nom du paramètre de pagination par défaut de Laravel

            $('#ddlNbParPage').change(function () {
                changerParam('NbParPage', this.value);
            });

            $('#btnAfficherAvance').click(function () {
                booAfficherAvance = !booAfficherAvance;
                this.innerText = booAfficherAvance ? '-' : '+';

                if (booAfficherAvance)
                    $('#divRechercheAvancé').slideDown(200);
                else
                    $('#divRechercheAvancé').slideUp(200);
            });

            // Initialiser l'état de la recherche avancée au chargement de la page
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('Auteur') || urlParams.has('Categorie') || urlParams.has('DateDebut') || urlParams.has('DateFin')) {
                booAfficherAvance = true;
                $('#btnAfficherAvance').text('-');
                $('#divRechercheAvancé').slideDown(0);
            } else {
                $('#divRechercheAvancé').slideUp(0);
            }

            // Pour que les ordres et descriptions soient soumis avec le formulaire
            $('#TypeOrdre, #Ordre, #Description, #Auteur, #CategorieSelect, #DateDebut, #DateFin').change(function() {
                // Pas besoin de changerParam ici car le formulaire sera soumis via le bouton de recherche
                // ou via ddlNbParPage/ddlPage. Cela assure que les valeurs sont à jour.
            });
        });
    </script>
@endsection
