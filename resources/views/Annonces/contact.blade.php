@extends('layouts.app')

@section('title', 'Contacter l\'auteur de ' . $annonce->DescriptionAbregee)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Contacter l'auteur de l'annonce : **{{ $annonce->DescriptionAbregee }}**</div>

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
                            <div class="alert alert-info" role="alert">
                                Vous devez être connecté pour envoyer un message. <a href="{{ route('login') }}">Connectez-vous ici</a>.
                            </div>
                        @endguest

                        @auth
                            <form method="POST" action="{{ route('annonces.contact.send', $annonce->NoAnnonce) }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="sujet" class="form-label">Sujet du message :</label>
                                    <input type="text" class="form-control @error('sujet') is-invalid @enderror" id="sujet" name="sujet" value="{{ old('sujet') }}" required>
                                    @error('sujet')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Votre message :</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Envoyer le message</button>
                                <a href="{{ route('annonces.show', $annonce->NoAnnonce) }}" class="btn btn-secondary">Retour à l'annonce</a>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
