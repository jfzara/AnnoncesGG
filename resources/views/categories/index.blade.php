@extends('layouts.app')

@section('title', 'Liste des Catégories')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0">Toutes les Catégories</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($categories->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            Aucune catégorie n'a été trouvée pour le moment.
                        </div>
                    @else
                        <ul class="list-group">
                            @foreach ($categories as $categorie)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $categorie->Description }}
                                    <span class="badge bg-primary rounded-pill">{{ $categorie->NoCategorie }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('categories.create') }}" class="btn btn-success"><i class="fas fa-plus-circle"></i> Ajouter une nouvelle catégorie</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
