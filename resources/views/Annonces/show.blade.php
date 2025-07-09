@extends('layouts.app')

@section('title', $annonce->Titre ?? 'Détails de l\'Annonce')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">{{ $annonce->Titre ?? 'Annonce sans titre' }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Catégorie :</strong> {{ $annonce->categorie->Description ?? 'N/A' }}
                    </div>
                    <div class="mb-3">
                        <strong>Prix :</strong> {{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit / À discuter' }}
                    </div>
                    <div class="mb-3">
                        <strong>Description :</strong>
                        <p>{{ $annonce->DescriptionComplete ?? $annonce->DescriptionAbregee ?? 'Pas de description.' }}</p>
                    </div>

                    @if($annonce->Photo)
                        <div class="mb-3 text-center">
                            <img src="{{ asset('storage/' . $annonce->Photo) }}" class="img-fluid rounded" alt="Photo de l'annonce" style="max-height: 400px;">
                        </div>
                    @else
                        <div class="mb-3 text-center text-muted">
                            <p>Aucune photo disponible pour cette annonce.</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Parution :</strong> {{ $annonce->Parution->format('d/m/Y') }}
                    </div>
                    @if($annonce->MiseAJour)
                        <div class="mb-3">
                            <strong>Dernière mise à jour :</strong> {{ $annonce->MiseAJour->format('d/m/Y H:i') }}
                        </div>
                    @endif
                    @if($annonce->DateFin)
                        <div class="mb-3">
                            <strong>Date d'expiration :</strong> {{ $annonce->DateFin->format('d/m/Y') }}
                            @if($annonce->DateFin->isPast())
                                <span class="badge bg-danger ms-2">Expirée</span>
                            @endif
                        </div>
                    @else
                        <div class="mb-3">
                            <strong>Date d'expiration :</strong> Aucune (active indéfiniment)
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Publié par :</strong> {{ $annonce->user->name ?? 'Utilisateur inconnu' }}
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('annonces.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour aux annonces</a>
                        {{-- Ajoutez des boutons d'édition/suppression ici si l'utilisateur est autorisé --}}
                        @auth
                            @if(Auth::id() == $annonce->NoUtilisateur)
                                <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn btn-warning ms-2"><i class="fas fa-edit"></i> Modifier</a>
                                {{-- Formulaire de suppression si nécessaire --}}
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
