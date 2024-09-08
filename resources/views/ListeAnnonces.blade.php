<!-- resources/views/ListeAnnonces.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <title>FAF - Projet 03</title>
</head>
<body>
<nav class="bg-dark navbar navbar-expand-md row">
    <div class="container">
        <div id="menu" class="collapse navbar-collapse justify-content-center">
            <div class="navbar-nav">
                <a href="ListeAnnonces.php" class="nav-item nav-link text-light">Annonces</a>
                <a href="GestionAnnonces.php" class="nav-item nav-link text-light">Gestion de vos annonces</a>
                <a href="miseAJourProfil.php" class="nav-item nav-link text-light">Modification du profil</a>
                <a href="Deconnexion.php" class="nav-item nav-link text-light">Déconnexion</a>
                <span class="text-light text-center align-middle m-auto">(test@test.test)</span>
            </div>
        </div>
    </div>
</nav>
<br><br>
<script>
    let booAfficherAvance = false;

    function changerParam(strParam, strValeur) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        urlParams.set(strParam, strValeur);

        window.location = './ListeAnnonces.php?' + urlParams.toString();
    }

    let intPage = 1;
    let intPageMax = 4;

    if (intPageMax > 0 && intPage > intPageMax)
        changerParam('Page', intPageMax);

    $(document).ready(() => {
        $('#ddlPage').change(function () {
            changerParam('Page', this.value);
        });

        $('#ddlNbParPage').change(function () {
            changerParam('NbParPage', this.value);
        });

        $('#btnAfficherAvance').click(function () {
            booAfficherAvance = !booAfficherAvance;
            this.innerText = booAfficherAvance ? '-' : '+';

            if (booAfficherAvance)
                $('#divRechercheAvancé').slideDown(0);
            else
                $('#divRechercheAvancé').slideUp(0);
        });

        $('#divRechercheAvancé').slideUp(0);
    });
</script>

<style>
    .annonce {
        width: 300px;
        height: 400px;
    }

    .imageSize {
        height: 250px;
    }
</style>



    <div id="divPanel" class="d-flex">
    <div id="divNbParPage" class="ml-3 text-left flex-fill">
        <div class="d-inline-flex" style="width: 100%">
            <label class="col-form-label">Éléments par page : </label>
            <select id="ddlNbParPage" class="form-control form-control-sm col-1 my-auto mx-2 p-0">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
            </select>
        </div>
        <h5 class="text-secondary font-italic">20 annonces trouvées.</h5>
    </div>
    <div id="divRecherche" class="flex-fill">
        <div class="text-left float-right" style="width: 50%">
            <form id="frmRecherche" class="d-flex flex-column" method="GET" action="ListeAnnonces.php">
                <input id="NbParPage" name="NbParPage" type="hidden" value="">
                <input id="Page" name="Page" type="hidden" value="">
                <div id="divRechercheSimple">
                    <div class="form-group d-inline-flex my-0">
                        <label class="col-form-label">Ordre : </label>
                        <div class="my-auto mx-1">
                            <select class="form-control form-control-sm" id="TypeOrdre" name="TypeOrdre">
                                <option value="Date">Date</option>
                                <option value="Auteur">Auteur</option>
                                <option value="Categorie">Catégorie</option>
                            </select>
                        </div>
                        <div class="m-auto">
                            <select class="form-control form-control-sm" id="Ordre" name="Ordre">
                                <option value="ASC">▲</option>
                                <option value="DESC">▼</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group d-inline-flex mx-2 my-0">
                        <div class="m-auto mx-1">
                            <input class="form-control form-control-sm" type="text" value="" id="Description" name="Description">
                        </div>
                    </div>

                    <input class="btn btn-primary form-control-sm m-auto" type="submit" value="Rechercher">
                    <button id="btnAfficherAvance" type="button" class="btn btn-secondary font-weight-bold form-control-sm">+</button>
                </div>

                <div id="divRechercheAvancé" class="col-12 mt-2 border pt-2 pr-5" style="display: none;">
                    <div class="form-group row">
                        <label class="col-3">Auteur :</label>
                        <input class="col form-control form-control-sm" type="text" id="Auteur" name="Auteur" value="">
                    </div>
                    <div class="form-group row">
                        <label class="col-3">Catégorie :</label>
                        <select class="col form-control form-control-sm" id="Categorie" name="Categorie">
                            <option value="">Toutes</option>
                            <option value="1">Location</option>
                            <option value="2">Recherche</option>
                            <option value="3">À vendre</option>
                            <option value="4">À donner</option>
                            <option value="5">Service offert</option>
                            <option value="6">Autre</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label class="col-3">Date :</label>
                        <input class="col form-control form-control-sm mx-1" type="date" id="DateDebut" name="DateDebut" value="">
                        <p class="p-0 m-auto">à</p>
                        <input class="col form-control form-control-sm mx-1" type="date" id="DateFin" name="DateFin" value="">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<hr>

<div id="divListe" class="d-flex flex-wrap justify-content-around mt-2 border-secondary">


<div id="divAnnonce-1" class="m-3">
        <div class="card annonce">
            <div class="card-header d-flex justify-content-between py-1">
                <div class="text-left">#1</div>
                <div class="text-right">À vendre</div>
            </div>
            <div class="overflow-hidden text-right imageSize">
            <img src="{{ asset('photos-annonce/velo-route.jpg') }}"  alt="Vélo de montagne" width="300" class="m-auto">
            </div>
            <div class="card-body pb-1">
                <h6 class="card-title"><a href="Annonce.php?id=1">Vélo de montagne</a></h6>
                <div class="d-flex justify-content-between">
                    <div class="text-left">
                        <a href="mailto:exemple1@test.test">Jean Dupont</a>
                    </div>
                    <div class="text-right font-weight-bold"><span>150.00 $</span></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between py-0">
                <div class="text-left">2024-09-01 10:00:00</div>
                <div class="text-right font-italic">3</div>
            </div>
        </div>
    </div>

   <!--  <div id="divAnnonce-2" class="m-3">
        <div class="card annonce">
            <div class="card-header d-flex justify-content-between py-1">
                <div class="text-left">#2</div>
                <div class="text-right">Location</div>
            </div>
            <div class="overflow-hidden text-right imageSize">
                <img alt="Image de l'annonce 2" src="photos-annonce/test2.jpg" width="300" class="m-auto">
            </div>
            <div class="card-body pb-1">
                <h6 class="card-title"><a href="Annonce.php?id=2">Appartement en centre-ville</a></h6>
                <div class="d-flex justify-content-between">
                    <div class="text-left">
                        <a href="mailto:exemple2@test.test">Marie Curie</a>
                    </div>
                    <div class="text-right font-weight-bold"><span>850.00 $/mois</span></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between py-0">
                <div class="text-left">2024-09-02 11:30:00</div>
                <div class="text-right font-italic">2</div>
            </div>
        </div>
    </div>

    <div id="divAnnonce-3" class="m-3">
        <div class="card annonce">
            <div class="card-header d-flex justify-content-between py-1">
                <div class="text-left">#3</div>
                <div class="text-right">À donner</div>
            </div>
            <div class="overflow-hidden text-right imageSize">
                <img alt="Image de l'annonce 3" src="photos-annonce/test3.jpg" width="300" class="m-auto">
            </div>
            <div class="card-body pb-1">
                <h6 class="card-title"><a href="Annonce.php?id=3">Canapé en bon état</a></h6>
                <div class="d-flex justify-content-between">
                    <div class="text-left">
                        <a href="mailto:exemple3@test.test">Sophie Martin</a>
                    </div>
                    <div class="text-right font-weight-bold"><span>Gratuit</span></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between py-0">
                <div class="text-left">2024-09-03 09:15:00</div>
                <div class="text-right font-italic">0</div>
            </div>
        </div>
    </div>

    <div id="divAnnonce-4" class="m-3">
        <div class="card annonce">
            <div class="card-header d-flex justify-content-between py-1">
                <div class="text-left">#4</div>
                <div class="text-right">Service offert</div>
            </div>
            <div class="overflow-hidden text-right imageSize">
                <img alt="Image de l'annonce 4" src="photos-annonce/test4.jpg" width="300" class="m-auto">
            </div>
            <div class="card-body pb-1">
                <h6 class="card-title"><a href="Annonce.php?id=4">Cours de guitare</a></h6>
                <div class="d-flex justify-content-between">
                    <div class="text-left">
                        <a href="mailto:exemple4@test.test">Pierre Lemoine</a>
                    </div>
                    <div class="text-right font-weight-bold"><span>30.00 $/heure</span></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between py-0">
                <div class="text-left">2024-09-04 14:00:00</div>
                <div class="text-right font-italic">1</div>
            </div>
        </div>
    </div>

    <div id="divAnnonce-5" class="m-3">
        <div class="card annonce">
            <div class="card-header d-flex justify-content-between py-1">
                <div class="text-left">#5</div>
                <div class="text-right">À vendre</div>
            </div>
            <div class="overflow-hidden text-right imageSize">
                <img alt="Image de l'annonce 5" src="photos-annonce/test5.jpg" width="300" class="m-auto">
            </div>
            <div class="card-body pb-1">
                <h6 class="card-title"><a href="Annonce.php?id=5">Table en bois massif</a></h6>
                <div class="d-flex justify-content-between">
                    <div class="text-left">
                        <a href="mailto:exemple5@test.test">Lucie Dupuis</a>
                    </div>
                    <div class="text-right font-weight-bold"><span>250.00 $</span></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between py-0">
                <div class="text-left">2024-09-05 16:20:00</div>
                <div class="text-right font-italic">2</div>
            </div>
        </div>
    </div>

    <div id="divAnnonce-6" class="m-3">
        <div class="card annonce">
            <div class="card-header d-flex justify-content-between py-1">
                <div class="text-left">#6</div>
                <div class="text-right">Recherche</div>
            </div>
            <div class="overflow-hidden text-right imageSize">
                <img alt="Image de l'annonce 6" src="photos-annonce/test6.jpg" width="300" class="m-auto">
            </div>
            <div class="card-body pb-1">
                <h6 class="card-title"><a href="Annonce.php?id=6">Recherche babysitter</a></h6>
                <div class="d-flex justify-content-between">
                    <div class="text-left">
                        <a href="mailto:exemple6@test.test">Anna Lefebvre</a>
                    </div>
                    <div class="text-right font-weight-bold"><span>À discuter</span></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between py-0">
                <div class="text-left">2024-09-06 12:45:00</div>
                <div class="text-right font-italic">3</div>
            </div>
        </div>
    </div>

   
   
   
   



<div id="divAnnonce-1" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#1</div>
            <div class="text-right">À vendre</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 1" src="C:\wamp64\www\Zara-projetfinal\Zara-projetfinal\photos-annonce\velo-route.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=1">Vélo de montagne</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple1@test.test">Jean Dupont</a>
                </div>
                <div class="text-right font-weight-bold"><span>150.00 $</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
            <div class="text-left">2024-09-01 10:00:00</div>
            <div class="text-right font-italic">3</div>
        </div>
    </div>
</div>

<div id="divAnnonce-2" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#2</div>
            <div class="text-right">Location</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 2" src="photos-annonce/artisanat.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=2">Appartement en centre-ville</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple2@test.test">Marie Curie</a>
                </div>
                <div class="text-right font-weight-bold"><span>850.00 $/mois</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
            <div class="text-left">2024-09-02 11:30:00</div>
            <div class="text-right font-italic">2</div>
        </div>
    </div>
</div>

<div id="divAnnonce-3" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#3</div>
            <div class="text-right">À donner</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 3" src="C:\wamp64\www\Zara-projetfinal\Zara-projetfinal\photos-annonce\Sofa.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=3">Canapé en bon état</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple3@test.test">Sophie Martin</a>
                </div>
                <div class="text-right font-weight-bold"><span>Gratuit</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
            <div class="text-left">2024-09-03 09:15:00</div>
            <div class="text-right font-italic">0</div>
        </div>
    </div>
</div>

<div id="divAnnonce-4" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#4</div>
            <div class="text-right">Service offert</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 4" src="photos-annonce/Consoles.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=4">Cours de guitare</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple4@test.test">Pierre Lemoine</a>
                </div>
                <div class="text-right font-weight-bold"><span>30.00 $/heure</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
        <div class="text-left">2024-09-04 16:00:00</div>
            <div class="text-right font-italic">1</div>
        </div>
    </div>
</div>

<div id="divAnnonce-5" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#5</div>
            <div class="text-right">À vendre</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 5" src="photos-annonce/Ordinateur.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=5">Ordinateur portable</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple5@test.test">Claire Dubois</a>
                </div>
                <div class="text-right font-weight-bold"><span>500.00 $</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
            <div class="text-left">2024-09-05 14:20:00</div>
            <div class="text-right font-italic">5</div>
        </div>
    </div>
</div>

<div id="divAnnonce-6" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#6</div>
            <div class="text-right">Recherche</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 6" src="photos-annonce/Tables.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=6">Recherche table à manger</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple6@test.test">Marc Tremblay</a>
                </div>
                <div class="text-right font-weight-bold"><span>N/A</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
            <div class="text-left">2024-09-06 09:45:00</div>
            <div class="text-right font-italic">7</div>
        </div>
    </div>
</div>

<div id="divAnnonce-7" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#7</div>
            <div class="text-right">À vendre</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 7" src="photos-annonce/Tv.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=7">Télévision 4K</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple7@test.test">Julie Lambert</a>
                </div>
                <div class="text-right font-weight-bold"><span>300.00 $</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
            <div class="text-left">2024-09-07 18:00:00</div>
            <div class="text-right font-italic">4</div>
        </div>
    </div>
</div>

<div id="divAnnonce-8" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#8</div>
            <div class="text-right">À donner</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 8" src="photos-annonce/Vetements.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=8">Vêtements pour enfants</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple8@test.test">Nathalie Roy</a>
                </div>
                <div class="text-right font-weight-bold"><span>Gratuit</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
            <div class="text-left">2024-09-08 13:30:00</div>
            <div class="text-right font-italic">9</div>
        </div>
    </div>
</div>

<div id="divAnnonce-9" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#9</div>
            <div class="text-right">Service offert</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 9" src="photos-annonce/Cuisine.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=9">Cours de cuisine</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple9@test.test">Charles Gagnon</a>
                </div>
                <div class="text-right font-weight-bold"><span>50.00 $/cours</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
            <div class="text-left">2024-09-09 15:45:00</div>
            <div class="text-right font-italic">6</div>
        </div>
    </div>
</div>

<div id="divAnnonce-10" class="m-3">
    <div class="card annonce">
        <div class="card-header d-flex justify-content-between py-1">
            <div class="text-left">#10</div>
            <div class="text-right">À vendre</div>
        </div>
        <div class="overflow-hidden text-right imageSize">
            <img alt="Image de l'annonce 10" src="photos-annonce/Livre.jpg" width="300" class="m-auto">
        </div>
        <div class="card-body pb-1">
            <h6 class="card-title"><a href="Annonce.php?id=10">Livres de collection</a></h6>
            <div class="d-flex justify-content-between">
                <div class="text-left">
                    <a href="mailto:exemple10@test.test">Isabelle Tremblay</a>
                </div>
                <div class="text-right font-weight-bold"><span>100.00 $</span></div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-0">
            <div class="text-left">2024-09-10 17:30:00</div>
            <div class="text-right font-italic">8</div>
        </div>
    </div>
</div> 
   
Ceci est un commentaire HTML -->

</div>