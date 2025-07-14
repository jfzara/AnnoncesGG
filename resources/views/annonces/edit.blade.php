@extends('layouts.app')

@section('title', 'Modifier l\'Annonce - ' . $annonce->Titre) {{-- Ajout du titre de l'annonce pour une meilleure clarté --}}

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0"> {{-- Ajout d'une ombre et suppression de la bordure --}}
                {{-- Utilisation de custom-gradient-header --}}
                <div class="card-header custom-gradient-header">
                    <i class="fas fa-edit fa-lg me-3"></i> {{-- Icône pour symboliser la modification --}}
                    <h3 class="mb-0 text-white">Modifier l'Annonce : <span class="yellow-text">{{ $annonce->DescriptionAbregee }}</span></h3> {{-- Ajout du titre de l'annonce en jaune --}}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('annonces.update', $annonce->NoAnnonce) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Champ Titre --}}
                        <div class="mb-3">
                            <label for="Titre" class="form-label">Titre de l'Annonce <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Titre') is-invalid @enderror" id="Titre" name="Titre" value="{{ old('Titre', $annonce->Titre) }}" required placeholder="Titre de votre annonce">
                            @error('Titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Description Abrégée --}}
                        <div class="mb-3">
                            <label for="DescriptionAbregee" class="form-label">Description Abrégée <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('DescriptionAbregee') is-invalid @enderror" id="DescriptionAbregee" name="DescriptionAbregee" value="{{ old('DescriptionAbregee', $annonce->DescriptionAbregee) }}" required placeholder="Bref résumé de votre annonce">
                            @error('DescriptionAbregee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Description Complète --}}
                        <div class="mb-3">
                            <label for="DescriptionComplete" class="form-label">Description Complète</label>
                            <textarea class="form-control @error('DescriptionComplete') is-invalid @enderror" id="DescriptionComplete" name="DescriptionComplete" rows="5" placeholder="Description détaillée de votre article">{{ old('DescriptionComplete', $annonce->DescriptionComplete) }}</textarea>
                            @error('DescriptionComplete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Prix --}}
                        <div class="mb-3">
                            <label for="Prix" class="form-label">Prix ($)</label>
                            <input type="text" {{-- Changé à 'text' pour le pattern, comme dans la création --}}
                                   step="0.01" {{-- step est pertinent pour un input type="number", mais le pattern est prioritaire avec type="text" --}}
                                   class="form-control @error('Prix') is-invalid @enderror"
                                   id="Prix"
                                   name="Prix"
                                   value="{{ old('Prix', $annonce->Prix) }}"
                                   placeholder="Ex: 124.59 (Laissez vide si gratuit)"
                                   pattern="^\d+(\.\d{1,2})?$"
                                   title="Veuillez entrer un nombre avec ou sans deux décimales (ex: 124 ou 124.59)">
                            @error('Prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ DateFin --}}
                        <div class="mb-3">
                            <label for="DateFin" class="form-label">Date d'expiration de l'annonce (optionnel) :</label>
                            <input type="date"
                                   class="form-control @error('DateFin') is-invalid @enderror"
                                   id="DateFin"
                                   name="DateFin"
                                   value="{{ old('DateFin', $annonce->DateFin ? $annonce->DateFin->format('Y-m-d') : '') }}">
                            @error('DateFin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Catégorie --}}
                        <div class="mb-3">
                            <label for="Categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-select @error('Categorie') is-invalid @enderror" id="Categorie" name="Categorie" required>
                                <option value="">-- Sélectionnez une catégorie --</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->NoCategorie }}" {{ old('Categorie', $annonce->NoCategorie) == $categorie->NoCategorie ? 'selected' : '' }}> {{-- Utilisation de $annonce->NoCategorie pour pré-sélectionner --}}
                                        {{ $categorie->Description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Photo (gestion de l'upload et affichage de l'image existante) --}}
                        <div class="mb-3">
                            <label for="photo_annonce" class="form-label">Photo de l'Annonce</label>
                            <input type="file" class="form-control @error('photo_annonce') is-invalid @enderror" id="photo_annonce" name="photo_annonce" accept="image/*">
                            @error('photo_annonce')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if($annonce->Photo)
                                <div class="mt-3">
                                    <p class="fw-bold mb-2">Photo actuelle :</p>
                                    <img src="{{ asset('storage/' . $annonce->Photo) }}" alt="Photo actuelle de l'annonce" class="img-fluid rounded shadow-sm" style="max-width: 200px; height: auto;">
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" name="delete_current_image" id="delete_current_image" value="1">
                                        <label class="form-check-label" for="delete_current_image">
                                            Supprimer la photo actuelle
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <small class="form-text text-muted mt-2">Formats acceptés : JPG, PNG, GIF, SVG. Taille maximale : 2 Mo.</small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            {{-- Utilisation de custom-primary-button --}}
                            <button type="submit" class="btn custom-primary-button">
                                <i class="fas fa-save me-2"></i> Mettre à jour l'Annonce
                            </button>
                            {{-- Bouton d'annulation avec icône, retourne à la gestion des annonces --}}
                            <a href="{{ route('annonces.gestion') }}" class="btn btn-outline-secondary">
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

@section('styles')
{{-- Aucun style spécifique nécessaire ici, tous les styles sont centralisés dans layouts/app.blade.php --}}
@endsection
