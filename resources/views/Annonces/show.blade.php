{{-- Bouton "Contacter l'auteur" --}}
<div class="mt-4">
    @auth
        {{-- S'assurer que l'utilisateur n'essaie pas de se contacter lui-même --}}
        @if (Auth::id() !== $annonce->NoUtilisateur)
            <a href="{{ route('annonces.contact.create', $annonce->NoAnnonce) }}" class="btn btn-primary">
                <i class="fas fa-envelope"></i> Contacter l'auteur
            </a>
        @else
            <button class="btn btn-info" disabled>Ceci est votre annonce</button>
        @endif
    @else
        <button class="btn btn-primary" disabled title="Connectez-vous pour contacter l'auteur">Contacter l'auteur</button>
        <p class="text-muted mt-2">Connectez-vous pour contacter l'auteur de cette annonce.</p>
    @endauth
</div>
