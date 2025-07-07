@extends('layouts.app') {{-- Assurez-vous que votre layout principal est 'layouts.app' --}}

@section('title', 'Gestion de vos annonces - AnnoncesGG')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h1 class="mb-4">Gestion de vos annonces</h1>

            <p class="lead">
                Bienvenue sur votre espace de gestion d'annonces. Ici, vous pouvez consulter toutes vos annonces, en ajouter de nouvelles, ou modifier et supprimer celles qui existent déjà.
            </p>

            <hr>

            <div class="card mb-4">
                <div class="card-header">
                    Vos annonces actuelles
                </div>
                <div class="card-body">
                    @if (true) {{-- Remplacez 'true' par une vérification de l'existence d'annonces pour l'utilisateur --}}
                        <div class="alert alert-info" role="alert">
                            **Fonctionnalité à venir !** Cette section affichera la liste de vos annonces.
                            Vous pourrez les modifier, les supprimer ou les désactiver.
                        </div>
                        {{-- Placeholder pour la liste des annonces (à remplacer par une boucle réelle) --}}
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Annonce #1 : Titre de l'annonce - <span class="badge badge-primary badge-pill">En ligne</span>
                                <div>
                                    <button class="btn btn-sm btn-info">Modifier</button>
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Annonce #2 : Autre titre - <span class="badge badge-warning badge-pill">En attente</span>
                                <div>
                                    <button class="btn btn-sm btn-info">Modifier</button>
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </div>
                            </li>
                        </ul>
                    @else
                        <p>Vous n'avez pas encore publié d'annonces.</p>
                    @endif
                    <a href="#" class="btn btn-success mt-3">Ajouter une nouvelle annonce</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Statistiques rapides
                </div>
                <div class="card-body">
                    <p>Total d'annonces publiées : <span>0</span></p>
                    <p>Annonces actives : <span>0</span></p>
                    <p>Annonces en attente : <span>0</span></p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
