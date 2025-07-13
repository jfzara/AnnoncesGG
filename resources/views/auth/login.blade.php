@extends('layouts.app')

@section('title', 'Connexion - TrouveTout')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg custom-auth-card border-0"> {{-- Carte avec ombre, style personnalisé et sans bordure --}}
                <div class="card-header custom-gradient-header text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i> {{ __('Connectez-vous à votre compte') }}</h4> {{-- Icône et texte centré --}}
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

                    {{-- Affichage des messages de session (ex: succès de l'inscription) --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('status') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label custom-label-auth">{{ __('Adresse E-mail') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg custom-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Entrez votre adresse e-mail">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label custom-label-auth">{{ __('Mot de passe') }}</label>
                            <input id="password" type="password" class="form-control form-control-lg custom-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Entrez votre mot de passe">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check"> {{-- Augmentation de la marge inférieure --}}
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label custom-label-auth" for="remember"> {{-- Label personnalisé --}}
                                {{ __('Se souvenir de moi') }}
                            </label>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn custom-primary-button btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> {{ __('Se connecter') }}
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link custom-link-auth mb-2" href="{{ route('password.request') }}">
                                    {{ __('Mot de passe oublié ?') }}
                                </a>
                            @endif
                            <p class="mb-0 text-muted">Pas encore de compte ? <a href="{{ route('register') }}" class="custom-link-auth fw-bold">Inscrivez-vous ici</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
