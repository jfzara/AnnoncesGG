@extends('layouts.app')

@section('title', 'Tableau de bord - TrouveTout') {{-- Titre mis à jour --}}

@section('content')
<div class="container my-5"> {{-- Ajout de marges globales --}}
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7"> {{-- Ajustement de la largeur pour un meilleur centrage --}}
            <div class="card shadow-lg border-0"> {{-- Card avec ombre et sans bordure --}}
                <div class="card-header custom-gradient-header text-white text-center py-3"> {{-- Utilisation de notre en-tête dégradé --}}
                    <h3 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> {{ __('Votre Tableau de Bord') }}</h3> {{-- Icône et texte centré --}}
                </div>

                <div class="card-body p-4 text-center"> {{-- Augmentation du padding et centrage du texte --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center justify-content-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('status') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <h4 class="mb-4 text-primary">{{ __('Bienvenue sur TrouveTout !') }}</h4> {{-- Message de bienvenue stylisé --}}
                    <p class="lead mb-4">
                        {{ __('Vous êtes connecté(e) et prêt(e) à explorer toutes les fonctionnalités de notre plateforme.') }}
                    </p>

                    <div class="row g-3"> {{-- Utilisation de g-3 pour l'espacement des colonnes --}}
                        <div class="col-md-6">
                            <a href="{{ route('annonces.create') }}" class="btn custom-primary-button btn-lg w-100 py-3"> {{-- Bouton large et stylisé --}}
                                <i class="fas fa-plus-circle me-2"></i> {{ __('Publier une nouvelle annonce') }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('annonces.manage') }}" class="btn custom-secondary-button btn-lg w-100 py-3"> {{-- Bouton large et stylisé --}}
                                <i class="fas fa-list-alt me-2"></i> {{ __('Gérer mes annonces') }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('messages.index') }}" class="btn btn-info btn-lg w-100 py-3 text-white"> {{-- Bouton stylisé --}}
                                <i class="fas fa-comments me-2"></i> {{ __('Mes conversations') }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-dark btn-lg w-100 py-3"> {{-- Bouton stylisé --}}
                                <i class="fas fa-user-circle me-2"></i> {{ __('Modifier mon profil') }}
                            </a>
                        </div>
                    </div>

                    <p class="mt-4 text-muted">
                        {{ __('Commencez par parcourir les annonces ou publiez la vôtre !') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
