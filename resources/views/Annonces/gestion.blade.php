@extends('layouts.app')

@section('title', 'Mes Annonces - AnnoncesGG')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Mes Annonces</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @forelse ($annonces as $annonce)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $annonce->Titre }}</h5>
                                <p class="card-text">{{ $annonce->DescriptionAbregee }}</p>
                                <p class="card-text"><small class="text-muted">Catégorie: {{ $annonce->categorie->Description }}</small></p>
                                <p class="card-text"><small class="text-muted">Prix: {{ $annonce->Prix ? number_format($annonce->Prix, 2, ',', ' ') . ' $' : 'Gratuit' }}</small></p>
                                <p class="card-text"><small class="text-muted">Publié le: {{ $annonce->Parution->format('d/m/Y') }}</small></p>
                                @if($annonce->DateFin)
                                    <p class="card-text"><small class="text-muted">Expire le: {{ $annonce->DateFin->format('d/m/Y') }}</small>
                                    @if($annonce->DateFin->isPast())
                                        <span class="badge bg-danger ms-2">Expirée</span>
                                    @endif
                                    </p>
                                @else
                                    <p class="card-text"><small class="text-muted">Pas de date d'expiration</small></p>
                                @endif


                                <div class="mt-3">
                                    <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn btn-info btn-sm">Voir détails</a>
                                    <a href="{{ route('annonces.edit', $annonce->NoAnnonce) }}" class="btn btn-warning btn-sm">Modifier</a>

                                    <form action="{{ route('annonces.destroy', $annonce->NoAnnonce) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            Vous n'avez pas encore d'annonces actives.
                        </div>
                    @endforelse

                    <div class="mt-4">
                        {{ $annonces->links() }} {{-- Pour la pagination --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
