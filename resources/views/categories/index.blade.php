@extends('layouts.app')

@section('title', 'Toutes les Catégories')

@section('content')
    <h1>Liste des Catégories</h1>

    @auth
        <div class="mb-3">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Créer une nouvelle catégorie</a>
        </div>
    @endauth

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($categories->isEmpty())
        <p>Aucune catégorie n'a été trouvée.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $categorie)
                    <tr>
                        <td>{{ $categorie->NoCategorie }}</td>
                        <td>{{ $categorie->Description }}</td>
                        <td>
                            @auth
                                {{-- Si vous voulez permettre l'édition et suppression des catégories --}}
                                <a href="{{ route('categories.edit', $categorie->NoCategorie) }}" class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('categories.destroy', $categorie->NoCategorie) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">Supprimer</button>
                                </form>
                            @endauth
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
