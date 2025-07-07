@extends('layouts.app')

@section('title', 'Créer une Catégorie')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Ajouter une nouvelle Catégorie</h3>
                </div>
                <div class="card-body">
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

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="Description" class="form-label">Nom de la Catégorie <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Description') is-invalid @enderror" id="Description" name="Description" value="{{ old('Description') }}" required maxlength="20" autofocus>
                            @error('Description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Ex: Électronique, Immobilier (max. 20 caractères).</small>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary me-md-2"><i class="fas fa-save"></i> Enregistrer</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
