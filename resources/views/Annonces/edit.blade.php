@extends('layouts.app') {{-- Assurez-vous d'avoir un layout appelé 'app' --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modifier l\'Annonce') }}</div>

                <div class="card-body">
                    {{-- Le formulaire doit pointer vers la route 'annonces.update' --}}
                    {{-- et inclure l'ID de l'annonce que l'on modifie --}}
                    <form method="POST" action="{{ route('annonces.update', $annonce->NoAnnonce) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Indique à Laravel que c'est une requête PUT/PATCH --}}

                        {{-- Champ Titre --}}
                        <div class="mb-3">
                            <label for="Titre" class="form-label">Titre de l'Annonce</label>
                            <input type="text" class="form-control @error('Titre') is-invalid @enderror" id="Titre" name="Titre" value="{{ old('Titre', $annonce->Titre) }}" required>
                            @error('Titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Description Abrégée --}}
                        <div class="mb-3">
                            <label for="DescriptionAbregee" class="form-label">Description Abrégée</label>
                            <input type="text" class="form-control @error('DescriptionAbregee') is-invalid @enderror" id="DescriptionAbregee" name="DescriptionAbregee" value="{{ old('DescriptionAbregee', $annonce->DescriptionAbregee) }}" required>
                            @error('DescriptionAbregee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Description Complète --}}
                        <div class="mb-3">
                            <label for="DescriptionComplete" class="form-label">Description Complète</label>
                            <textarea class="form-control @error('DescriptionComplete') is-invalid @enderror" id="DescriptionComplete" name="DescriptionComplete" rows="5">{{ old('DescriptionComplete', $annonce->DescriptionComplete) }}</textarea>
                            @error('DescriptionComplete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Prix --}}
                        <div class="mb-3">
                            <label for="Prix" class="form-label">Prix (€)</label>
                            <input type="number" step="0.01" class="form-control @error('Prix') is-invalid @enderror" id="Prix" name="Prix" value="{{ old('Prix', $annonce->Prix) }}">
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
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Champ Catégorie --}}
                        <div class="mb-3">
                            <label for="Categorie" class="form-label">Catégorie</label>
                            <select class="form-select @error('Categorie') is-invalid @enderror" id="Categorie" name="Categorie" required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->NoCategorie }}" {{ old('Categorie', $annonce->Categorie) == $categorie->NoCategorie ? 'selected' : '' }}>
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
                            <input type="file" class="form-control @error('photo_annonce') is-invalid @enderror" id="photo_annonce" name="photo_annonce">
                            @error('photo_annonce')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if($annonce->Photo)
                                <div class="mt-2">
                                    <p>Photo actuelle :</p>
                                    <img src="{{ asset('storage/' . $annonce->Photo) }}" alt="Photo actuelle de l'annonce" style="max-width: 200px; height: auto;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="delete_current_image" id="delete_current_image" value="1">
                                        <label class="form-check-label" for="delete_current_image">
                                            Supprimer la photo actuelle
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour l'Annonce</button>
                        <a href="{{ route('annonces.gestion') }}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
