@extends('layouts.app')

@section('title', $annonce->Titre ?? 'Détails de l\'Annonce - TrouveTout')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0"> {{-- Card plus grande ombre et pas de bordure --}}
                <div class="card-header custom-gradient-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white"><i class="fas fa-bullhorn me-3"></i> {{ $annonce->Titre ?? 'Annonce sans titre' }}</h3>
                    <span class="badge bg-white text-primary fs-5 px-3 py-2 rounded-pill">{{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit' }}</span>
                </div>
                <div class="card-body">
                    {{-- Section photo --}}
                    @if($annonce->Photo)
                        <div class="mb-4 text-center">
                            {{-- MODIFICATION ICI : Utilisation directe de l'URL Cloudinary --}}
                            <img src="{{ $annonce->Photo }}" class="img-fluid rounded shadow-sm custom-detail-img" alt="Photo de l'annonce">
                        </div>
                    @else
                        <div class="mb-4 text-center text-muted border p-4 rounded bg-light">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p class="mb-0">Aucune photo disponible pour cette annonce.</p>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="fw-bold mb-1"><i class="fas fa-tag me-2 text-primary"></i> Catégorie :</p>
                            <p class="ms-4 mb-0">{{ $annonce->categorie->Description ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="fw-bold mb-1"><i class="fas fa-user-circle me-2 text-primary"></i> Publié par :</p>
                            <p class="ms-4 mb-0">{{ $annonce->user->name ?? 'Utilisateur inconnu' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <p class="fw-bold mb-2"><i class="fas fa-align-left me-2 text-primary"></i> Description Complète :</p>
                        <div class="card p-3 bg-light border-0">
                            <p class="mb-0 text-dark">{{ $annonce->DescriptionComplete ?? $annonce->DescriptionAbregee ?? 'Pas de description détaillée fournie.' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="fw-bold mb-1"><i class="fas fa-calendar-alt me-2 text-primary"></i> Date de parution :</p>
                            <p class="ms-4 mb-0">{{ $annonce->Parution->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($annonce->MiseAJour)
                                <p class="fw-bold mb-1"><i class="fas fa-history me-2 text-primary"></i> Dernière mise à jour :</p>
                                <p class="ms-4 mb-0">{{ $annonce->MiseAJour->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="fw-bold mb-1"><i class="fas fa-calendar-times me-2 text-primary"></i> Date d'expiration :</p>
                        <p class="ms-4 mb-0">
                            @if($annonce->DateFin)
                                {{ $annonce->DateFin->format('d/m/Y') }}
                                @if($annonce->DateFin->isPast())
                                    <span class="badge bg-danger ms-2"><i class="fas fa-exclamation-triangle me-1"></i> Expirée</span>
                                @else
                                    <span class="badge bg-info text-dark ms-2"><i class="fas fa-clock me-1"></i> Active</span>
                                @endif
                            @else
                                Aucune (active indéfiniment)
                            @endif
                        </p>
                    </div>

                    <div class="mt-4 border-top pt-3 d-flex justify-content-between align-items-center">
                        <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Retour aux annonces
                        </a>

                        @auth
                            @if(Auth::id() == $annonce->NoUtilisateur)
                                {{-- Boutons d'édition et suppression pour l'auteur de l'annonce --}}
                                <div class="d-flex gap-2">
                                    <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn custom-secondary-button">
                                        <i class="fas fa-edit me-2"></i> Modifier l'annonce
                                    </a>
                                    <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                            <i class="fas fa-trash-alt me-2"></i> Supprimer l'annonce
                                        </button>
                                    </form>
                                </div>
                            @else
                                {{-- Bouton pour contacter le vendeur si ce n'est pas sa propre annonce --}}
                                <a href="{{ route('messages.show', ['annonce' => $annonce->NoAnnonce, 'otherUser' => $annonce->user->id]) }}"
                                   class="btn btn-success custom-primary-button" title="Contacter le vendeur">
                                    <i class="fas fa-envelope me-2"></i> Contacter le vendeur
                                </a>
                            @endif
                        @else
                            {{-- Si l'utilisateur n'est pas authentifié --}}
                            <a href="{{ route('login') }}" class="btn btn-outline-primary" title="Connectez-vous pour contacter">
                                <i class="fas fa-sign-in-alt me-2"></i> Connectez-vous pour contacter
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
