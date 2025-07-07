



@extends('layouts.app') {{-- Assurez-vous que votre layout principal est 'layouts.app' --}}

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
                    {{-- Messages de succès/erreur (similaire à votre edit.blade.php) --}}
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
                        @csrf {{-- Jeton CSRF pour la sécurité --}}

                        <div class="mb-3">
                            <label for="DescriptionAbregee" class="form-label">Description Abrégée <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('DescriptionAbregee') is-invalid @enderror" id="DescriptionAbregee" name="DescriptionAbregee" value="{{ old('DescriptionAbregee') }}" required autofocus>
                            @error('DescriptionAbregee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="DescriptionComplete" class="form-label">Description Complète</label>
                            <textarea class="form-control @error('DescriptionComplete') is-invalid @enderror" id="DescriptionComplete" name="DescriptionComplete" rows="5">{{ old('DescriptionComplete') }}</textarea>
                            @error('DescriptionComplete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Prix" class="form-label">Prix ($)</label>
                            <input type="number" step="0.01" class="form-control @error('Prix') is-invalid @enderror" id="Prix" name="Prix" value="{{ old('Prix') }}">
                            @error('Prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-control @error('Categorie') is-invalid @enderror" id="Categorie" name="Categorie" required>
                                <option value="">Sélectionnez une catégorie</option>
                                {{-- La variable $categories est passée par le contrôleur AnnonceController@create --}}
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->NoCategorie }}" {{ old('Categorie') == $categorie->NoCategorie ? 'selected' : '' }}>
                                        {{ $categorie->Description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Photo" class="form-label">Image (Optionnel)</label>
                            <input type="file" class="form-control @error('Photo') is-invalid @enderror" id="Photo" name="Photo">
                            @error('Photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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
