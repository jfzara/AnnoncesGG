<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{-- Ajout de Font Awesome 5 pour les icônes (si vous voulez des icônes plus modernes que 4.7) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40hNPFNmhMIUTweLLgByJTM53jlkp0EZSCIDUPgKqgNHPDwzthHxpsiphon2WMjY/X7OQXQd/Jsw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Toutes les Annonces - AnnoncesGG</title>
</head>
<body>
<nav class="bg-dark navbar navbar-expand-md row">
    <div class="container">
        <div id="menu" class="collapse navbar-collapse justify-content-center">
            <div class="navbar-nav">
                <a href="{{ route('annonces.list') }}" class="nav-item nav-link text-light">Annonces</a>
                @auth
                    <a href="{{ route('gestion-annonces') }}" class="nav-item nav-link text-light">Gestion de vos annonces</a>
                    {{-- Si vous avez une route pour la modification de profil --}}
                    {{-- <a href="{{ route('profile.edit') }}" class="nav-item nav-link text-light">Modification du profil</a> --}}
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item nav-link text-light">Déconnexion</a>
                    <span class="text-light text-center align-middle m-auto ml-2">Bonjour, {{ Auth::user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="nav-item nav-link text-light">Connexion</a>
                    <a href="{{ route('register') }}" class="nav-item nav-link text-light">Inscription</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@auth
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endauth

<br><br>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
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
                    {{-- Le nombre total d'annonces doit venir du contrôleur --}}
                    <h5 class="text-secondary font-italic ms-3 mb-0">{{ $annonces->count() }} annonces trouvées.</h5>
                </div>
                <div id="divRecherche" class="flex-fill d-flex justify-content-end">
                    <form id="frmRecherche" class="d-flex flex-column" method="GET" action="{{ route('annonces.list') }}">
                        {{-- Champs cachés pour conserver les paramètres de pagination et nombre par page --}}
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
                            <div class="card annonce" style="width: 300px; height: 450px;"> {{-- Augmenté la hauteur pour les boutons --}}
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
                                    <p class="card-text text-muted flex-grow-1" style="font-size: 0.85em;">{{ Str::limit($annonce->DescriptionComplete, 50) }}</p> {{-- Ajouté un Str::limit --}}
                                    <div class="d-flex justify-content-between align-items-center mt-auto"> {{-- mt-auto pousse au bas --}}
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
                                        {{-- Boutons Modifier/Supprimer conditionnels pour le propriétaire --}}
                                        @auth
                                            {{-- Pour l'instant, seulement le propriétaire. L'admin viendra après. --}}
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

            {{-- Liens de pagination (à implémenter dans le contrôleur si vous ne l'avez pas déjà fait) --}}
            {{-- Si votre contrôleur utilise ->paginate(), vous pouvez ajouter ceci : --}}
            {{-- <div class="d-flex justify-content-center mt-4">
                {{ $annonces->links() }}
            </div> --}}
        </div>
    </div>
</div>

<script>
    // Script JavaScript existant, adapté pour Laravel
    let booAfficherAvance = false; // Par défaut, la recherche avancée est masquée

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
        $('#PageInput').val(new URLSearchParams(window.location.search).get('Page') || '1');


        $('#ddlNbParPage').change(function () {
            changerParam('NbParPage', this.value);
        });

        // La logique de pagination n'est pas directement gérée par ce script ici.
        // Si vous utilisez la pagination de Laravel (->paginate()), les liens générés par $annonces->links()
        // géreront automatiquement le paramètre 'Page'.
        // Si vous avez un ddlPage, vous devriez le lier comme ceci :
        // $('#ddlPage').change(function () {
        //     changerParam('Page', this.value);
        // });


        $('#btnAfficherAvance').click(function () {
            booAfficherAvance = !booAfficherAvance;
            this.innerText = booAfficherAvance ? '-' : '+';

            if (booAfficherAvance)
                $('#divRechercheAvancé').slideDown(200); // Utilise un peu d'animation
            else
                $('#divRechercheAvancé').slideUp(200); // Utilise un peu d'animation
        });

        // Initialiser l'état de la recherche avancée au chargement de la page
        // Si des paramètres de recherche avancée sont présents dans l'URL, l'afficher
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
</body>
</html>
