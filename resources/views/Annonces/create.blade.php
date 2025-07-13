@extends('layouts.app')

@section('title', 'Créer une Annonce - AnnoncesGG')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0"> {{-- Ajout d'une ombre et bordure retirée pour un look plus moderne --}}
                {{-- Application de la classe custom-gradient-header --}}
                <div class="card-header custom-gradient-header">
                    <i class="fas fa-bullhorn fa-lg me-3"></i> {{-- Icône pour symboliser la création d'annonce --}}
                    <h3 class="mb-0 text-white">Créer une nouvelle Annonce</h3> {{-- Le texte est déjà blanc grâce à custom-gradient-header --}}
                </div>
                <div class="card-body">
                    {{-- Le formulaire doit pointer vers la route 'annonces.store' --}}
                    <form action="{{ route('annonces.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf {{-- Jeton CSRF pour la sécurité --}}

                        {{-- Champ: Titre --}}
                        <div class="mb-3">
                            <label for="Titre" class="form-label">Titre de l'annonce <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('Titre') is-invalid @enderror"
                                   id="Titre"
                                   name="Titre"
                                   value="{{ old('Titre') }}"
                                   placeholder="Ex: Vélo de montagne à vendre"
                                   required
                                   maxlength="255">
                            @error('Titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Le titre principal de votre annonce.</small>
                        </div>

                        {{-- Champ: Description Abrégée --}}
                        <div class="mb-3">
                            <label for="DescriptionAbregee" class="form-label">Description Abrégée <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('DescriptionAbregee') is-invalid @enderror"
                                   id="DescriptionAbregee"
                                   name="DescriptionAbregee"
                                   value="{{ old('DescriptionAbregee') }}"
                                   placeholder="Ex: Excellent VTT, très peu utilisé."
                                   required
                                   maxlength="100">
                            @error('DescriptionAbregee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Un résumé concis pour votre annonce (max 100 caractères).</small>
                        </div>

                        {{-- Champ: Description Complète --}}
                        <div class="mb-3">
                            <label for="DescriptionComplete" class="form-label">Description Complète</label>
                            <textarea class="form-control @error('DescriptionComplete') is-invalid @enderror"
                                      id="DescriptionComplete"
                                      name="DescriptionComplete"
                                      rows="5"
                                      placeholder="Décrivez votre article en détail, son état, ses caractéristiques... (Facultatif, mais recommandé)">{{ old('DescriptionComplete') }}</textarea>
                            @error('DescriptionComplete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Fournissez tous les détails utiles pour les acheteurs potentiels.</small>
                        </div>

                        {{-- Champ: Prix --}}
                        <div class="mb-3">
                            <label for="Prix" class="form-label">Prix ($)</label>
                            <input type="text"
                                   class="form-control @error('Prix') is-invalid @enderror"
                                   id="Prix"
                                   name="Prix"
                                   value="{{ old('Prix') }}"
                                   placeholder="Ex: 124.59 (Laissez vide si gratuit)"
                                   pattern="^\d+(\.\d{1,2})?$"
                                   title="Veuillez entrer un nombre avec ou sans deux décimales (ex: 124 ou 124.59)">
                            @error('Prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Laissez vide si l'article est gratuit ou à discuter. Format: 123 ou 123.45.</small>
                        </div>

                        {{-- Champ: Date d'expiration --}}
                        <div class="mb-3">
                            <label for="DateFin" class="form-label">Date d'expiration de l'annonce (optionnel) :</label>
                            <input type="date"
                                   class="form-control @error('DateFin') is-invalid @enderror"
                                   id="DateFin"
                                   name="DateFin"
                                   value="{{ old('DateFin') }}">
                            @error('DateFin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">La date après laquelle l'annonce ne sera plus visible. Laissez vide pour aucune date d'expiration.</small>
                        </div>

                        {{-- Champ: Catégorie --}}
                        <div class="mb-3">
                            <label for="Categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-control @error('Categorie') is-invalid @enderror" id="Categorie" name="Categorie" required>
                                <option value="">-- Sélectionnez une catégorie --</option>
                                @forelse($categories as $categorie)
                                    <option value="{{ $categorie->NoCategorie }}" {{ old('Categorie') == $categorie->NoCategorie ? 'selected' : '' }}>
                                        {{ $categorie->Description }}
                                    </option>
                                @empty
                                    <option value="" disabled>Aucune catégorie disponible</option>
                                @endforelse
                            </select>
                            @error('Categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Choisissez la catégorie qui décrit le mieux votre annonce.</small>
                        </div>

                        {{-- Champ: Photo (nommé photo_annonce dans le contrôleur) --}}
                        <div class="mb-3">
                            <label for="photo_annonce" class="form-label">Ajouter une Photo (Optionnel)</label>
                            <input type="file"
                                   class="form-control @error('photo_annonce') is-invalid @enderror"
                                   id="photo_annonce"
                                   name="photo_annonce"
                                   accept="image/*">
                            @error('photo_annonce')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Formats acceptés : JPG, PNG, GIF, SVG. Taille maximale : 2 Mo.</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            {{-- Utilisation de la classe custom-primary-button --}}
                            <button type="submit" class="btn custom-primary-button me-md-2">
                                <i class="fas fa-plus-circle me-2"></i> Créer l'annonce
                            </button>
                            {{-- Bouton d'annulation avec icône --}}
                            <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times-circle me-2"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
