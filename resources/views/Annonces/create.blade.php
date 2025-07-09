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
                    {{-- Le bloc d'affichage des erreurs globales ($errors->any()) est supprimé ici
                        car les erreurs sont maintenant affichées en ligne sous chaque champ.
                        Les messages de succès/erreur de session seront gérés par des toasts. --}}

                    <form action="{{ route('annonces.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Champ: Titre --}}
                        {{-- Ajouté pour correspondre à la nouvelle colonne 'Titre' dans la DB --}}
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
                                   value="{{ old('DateFin') }}"> @error('DateFin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">La date après laquelle l'annonce ne sera plus visible. Laissez vide pour aucune date d'expiration.</small>
                        </div>

                        {{-- Champ: Catégorie --}}
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

                        {{-- Champ: Photo (nommé photo_annonce dans le contrôleur) --}}
                        <div class="mb-3">
                            <label for="photo_annonce" class="form-label">Ajouter une Photo (Optionnel)</label>
                            <input type="file"
                                   class="form-control @error('photo_annonce') is-invalid @enderror" {{-- Notez le 'photo_annonce' --}}
                                   id="photo_annonce" {{-- Notez le 'photo_annonce' --}}
                                   name="photo_annonce" {{-- C'est le 'name' que le contrôleur attend --}}
                                   accept="image/*">
                            @error('photo_annonce') {{-- Notez le 'photo_annonce' --}}
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Formats acceptés : JPG, PNG, GIF, SVG. Taille maximale : 2 Mo.</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-success me-md-2"><i class="fas fa-plus-circle"></i> Créer l'annonce</button>
                            <a href="{{ route('annonces.index') }}" class="btn btn-secondary"><i class="fas fa-times-circle"></i> Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
