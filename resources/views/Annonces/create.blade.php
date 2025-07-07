@extends('layouts.app')

@section('title', 'Créer une Annonce - AnnoncesGG')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Créer une nouvelle Annonce</h3>
                </div>
                <div class="card-body">
                    {{-- Messages de succès/erreur --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5>Erreurs de validation :</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('annonces.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Champ: Description Abrégée (VARCHAR 50) --}}
                        <div class="mb-3">
                            <label for="DescriptionAbregee" class="form-label">Titre / Description Abrégée <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('DescriptionAbregee') is-invalid @enderror"
                                   id="DescriptionAbregee"
                                   name="DescriptionAbregee"
                                   value="{{ old('DescriptionAbregee') }}"
                                   placeholder="Ex: Vélo de montagne à vendre"
                                   required
                                   autofocus
                                   maxlength="50"> {{-- Limite à 50 caractères --}}
                            @error('DescriptionAbregee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Un titre concis pour votre annonce (max. 50 caractères).</small>
                        </div>

                        {{-- Champ: Description Complète (VARCHAR 250) --}}
                        <div class="mb-3">
                            <label for="DescriptionComplete" class="form-label">Description Complète</label>
                            <textarea class="form-control @error('DescriptionComplete') is-invalid @enderror"
                                      id="DescriptionComplete"
                                      name="DescriptionComplete"
                                      rows="5" {{-- Agrandit la zone de texte --}}
                                      placeholder="Décrivez votre article en détail, son état, ses caractéristiques... (Max 250 caractères)"
                                      maxlength="250">{{-- Limite à 250 caractères --}}
                                {{ old('DescriptionComplete') }}</textarea>
                            @error('DescriptionComplete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Plus de détails sur votre annonce (max. 250 caractères, facultatif).</small>
                        </div>

                        {{-- Champ: Prix (DECIMAL 10,2) --}}
                        <div class="mb-3">
                            <label for="Prix" class="form-label">Prix ($)</label>
                            <input type="text"
                                   class="form-control @error('Prix') is-invalid @enderror"
                                   id="Prix"
                                   name="Prix"
                                   value="{{ old('Prix') }}"
                                   placeholder="Ex: 124.59 (optionnel)"
                                   pattern="^\d+(\.\d{1,2})?$" {{-- Permet des entiers ou des décimales avec 1 ou 2 chiffres après la virgule --}}
                                   title="Veuillez entrer un nombre avec ou sans deux décimales (ex: 124 ou 124.59)">
                            @error('Prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Laissez vide si l'article est gratuit ou à discuter. Format: 123 ou 123.45.</small>
                        </div>

                        {{-- Champ: Catégorie (référence NoCategorie de la table categories) --}}
                        <div class="mb-3">
                            <label for="Categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-control @error('Categorie') is-invalid @enderror" id="Categorie" name="Categorie" required>
                                <option value="">-- Sélectionnez une catégorie --</option>
                                {{-- Assurez-vous que $categories est bien passé depuis le contrôleur AnnonceController@create --}}
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

                        {{-- Champ: Photo (VARCHAR 50) --}}
                        <div class="mb-3">
                            <label for="Photo" class="form-label">Ajouter une Photo (Optionnel)</label>
                            <input type="file"
                                   class="form-control @error('Photo') is-invalid @enderror"
                                   id="Photo"
                                   name="Photo"
                                   accept="image/*"> {{-- Limite la sélection de fichiers aux images --}}
                            @error('Photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Formats acceptés : JPG, PNG, GIF, SVG. Max 2MB.</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-success me-md-2"><i class="fas fa-plus-circle"></i> Créer l'annonce</button>
                            <a href="{{ route('annonces.list') }}" class="btn btn-secondary"><i class="fas fa-times-circle"></i> Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
