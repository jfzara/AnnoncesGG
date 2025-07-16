@extends('layouts.app')

@section('title', 'Contacter l\'auteur de ' . $annonce->DescriptionAbregee)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header custom-gradient-header"> {{-- Applique le dégradé et le texte blanc --}}
                    {{-- Ajout d'une icône pour une meilleure identification --}}
                    <i class="fas fa-envelope fa-lg me-3"></i>
                    <h1 class="mb-0 h4">Contacter l'auteur de l'annonce : <span class="yellow-text fw-bold">{{ $annonce->DescriptionAbregee }}</span></h1>
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

                    @guest
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i> Vous devez être connecté pour envoyer un message. <a href="{{ route('login') }}" class="alert-link text-decoration-none fw-bold">Connectez-vous ici</a>.
                        </div>
                    @endguest

                    @auth
                        <form method="POST" action="{{ route('annonces.contact.send', $annonce->NoAnnonce) }}">
                            @csrf

                            <div class="mb-3">
                                <label for="sujet" class="form-label">Sujet du message :</label>
                                <input type="text"
                                       class="form-control @error('sujet') is-invalid @enderror"
                                       id="sujet"
                                       name="sujet"
                                       value="{{ old('sujet') }}"
                                       placeholder="Ex: Question sur le prix ou la disponibilité"
                                       required>
                                @error('sujet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Votre message :</label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message"
                                          name="message"
                                          rows="5"
                                          placeholder="Écrivez votre message ici..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                {{-- Utilise la classe custom-primary-button --}}
                                <button type="submit" class="btn custom-primary-button">
                                    <i class="fas fa-paper-plane me-2"></i> Envoyer le message
                                </button>
                                {{-- Bouton de retour avec l'icône --}}
                                <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Retour à l'annonce
                                </a>
                            </div>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
{{-- Aucun style spécifique nécessaire ici, tous les styles sont centralisés dans layouts/app.blade.php --}}
@endsection
