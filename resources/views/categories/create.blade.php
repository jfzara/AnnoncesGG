@extends('layouts.app')

@section('title', 'Créer une Catégorie - TrouveTout')

@section('content')
<div class="container mt-5"> {{-- Augmentation de la marge supérieure --}}
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0"> {{-- Card avec ombre et sans bordure --}}
                <div class="card-header custom-gradient-header text-white text-center py-3"> {{-- Utilisation de notre en-tête dégradé --}}
                    <h3 class="mb-0"><i class="fas fa-folder-plus me-2"></i> {{ __('Ajouter une nouvelle Catégorie') }}</h3> {{-- Icône et texte centré --}}
                </div>
                <div class="card-body p-4"> {{-- Augmentation du padding --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{-- Icône pour les erreurs --}}
                            <div>
                                <h5 class="mb-1">Erreurs de validation :</h5>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> {{-- Bouton de fermeture Bootstrap 5 --}}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{-- Icône pour le succès --}}
                            <div>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> {{-- Bouton de fermeture Bootstrap 5 --}}
                        </div>
                    @endif

                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="Description" class="form-label custom-label-auth">{{ __('Nom de la Catégorie') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg custom-form-control @error('Description') is-invalid @enderror" id="Description" name="Description" value="{{ old('Description') }}" required maxlength="20" autofocus placeholder="Ex: Électronique, Immobilier...">
                            @error('Description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted mt-2">Le nom de la catégorie (max. 20 caractères).</small> {{-- Amélioration du texte d'aide --}}
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4"> {{-- Utilisation de flexbox et gap pour les boutons --}}
                            <button type="submit" class="btn custom-primary-button btn-lg"> {{-- Bouton principal personnalisé et plus grand --}}
                                <i class="fas fa-plus-circle me-2"></i> {{ __('Ajouter la Catégorie') }} {{-- Icône plus pertinente --}}
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn custom-secondary-button btn-lg"> {{-- Bouton secondaire personnalisé et plus grand --}}
                                <i class="fas fa-arrow-left me-2"></i> {{ __('Retour aux Catégories') }} {{-- Icône et texte plus clairs --}}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
