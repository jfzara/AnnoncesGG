@extends('layouts.app')

@section('title', 'Vérifiez votre adresse e-mail - TrouveTout') {{-- Titre plus spécifique --}}

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg custom-auth-card border-0"> {{-- Carte avec ombre, style personnalisé et sans bordure --}}
                <div class="card-header custom-gradient-header text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i> {{ __('Vérifiez votre adresse e-mail') }}</h4> {{-- Icône et texte centré --}}
                </div>

                <div class="card-body p-4">
                    @if (session('resent'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class="text-center mb-4 text-muted">
                        {{ __('Avant de continuer, veuillez vérifier votre boîte de réception pour un lien de vérification.') }}
                    </p>
                    <p class="text-center mb-0 text-muted">
                        {{ __('Si vous n\'avez pas reçu l\'e-mail') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link custom-link-auth p-0 m-0 align-baseline fw-bold">{{ __('cliquez ici pour en demander un autre') }}</button>.
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
