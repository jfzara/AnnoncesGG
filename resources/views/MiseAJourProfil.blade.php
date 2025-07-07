@extends('layouts.app') {{-- Assurez-vous que votre layout principal est 'layouts.app' --}}

@section('title', 'Modification du profil - AnnoncesGG')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4">Modification du profil</h1>

            <p class="lead">
                Mettez à jour vos informations personnelles et votre mot de passe ici.
            </p>

            <hr>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT') {{-- Laravel utilise PUT pour les mises à jour --}}

                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Adresse Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                </div>

                <hr class="my-4">

                <h4>Changer le mot de passe (optionnel)</h4>

                <div class="form-group">
                    <label for="current_password">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                    <small class="form-text text-muted">Requis si vous modifiez l'email, le nom ou le mot de passe.</small>
                </div>

                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="form-text text-muted">Minimum 8 caractères.</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="btn btn-primary mt-3">Mettre à jour le profil</button>
            </form>

        </div>
    </div>
</div>
@endsection
