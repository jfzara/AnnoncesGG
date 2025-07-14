@extends('layouts.app')

@section('title', 'Inscription - TrouveTout')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg custom-auth-card border-0"> {{-- Carte avec ombre, style personnalisé et sans bordure --}}
                <div class="card-header custom-gradient-header text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> {{ __('Créez votre compte') }}</h4> {{-- Icône et texte centré --}}
                </div>
                <div class="card-body p-4">
                    {{-- Affichage des messages d'erreur de validation --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label custom-label-auth">{{ __('Nom d\'utilisateur') }}</label>
                            <input id="name" type="text" class="form-control form-control-lg custom-form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Votre nom d'utilisateur">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label custom-label-auth">{{ __('Adresse E-mail') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg custom-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Votre adresse e-mail">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label custom-label-auth">{{ __('Mot de passe') }}</label>
                            <input id="password" type="password" class="form-control form-control-lg custom-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Créez un mot de passe">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4"> {{-- Augmentation de la marge inférieure --}}
                            <label for="password_confirmation" class="form-label custom-label-auth">{{ __('Confirmer le mot de passe') }}</label>
                            <input id="password_confirmation" type="password" class="form-control form-control-lg custom-form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmez votre mot de passe">
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn custom-primary-button btn-lg">
                                <i class="fas fa-user-plus me-2"></i> {{ __('S\'inscrire') }}
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0 text-muted">Déjà un compte ? <a href="{{ route('login') }}" class="custom-link-auth fw-bold">Connectez-vous ici</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
