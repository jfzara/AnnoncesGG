@extends('layouts.app')

@section('title', 'CRÉER UNE ANNONCE - TROUVETOUT') {{-- Titre en majuscules --}}

@section('content')
<div class="container mt-4 mb-5"> {{-- Ajout de mb-5 pour plus d'espace en bas --}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card"> {{-- Suppression de shadow-sm et border-0 pour utiliser nos styles via CSS --}}
                <div class="card-header custom-gradient-header d-flex align-items-center"> {{-- Ajout de d-flex et align-items-center --}}
                    <i class="fas fa-bullhorn fa-lg me-3 text-white"></i> {{-- Icône en blanc --}}
                    <h3 class="mb-0 text-white">CRÉER UNE NOUVELLE ANNONCE</h3> {{-- Texte en majuscules --}}
                </div>
                <div class="card-body p-4"> {{-- Augmentation du padding interne pour plus d'aération --}}
                    <form action="{{ route('annonces.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Champ: Titre --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="Titre" class="form-label fw-bold">TITRE DE L'ANNONCE <span class="accent-text">*</span></label> {{-- Texte en majuscules, gras, et accentué --}}
                            <input type="text"
                                   class="form-control @error('Titre') is-invalid @enderror"
                                   id="Titre"
                                   name="Titre"
                                   value="{{ old('Titre') }}"
                                   placeholder="Ex: Vélo de montagne à vendre"
                                   required
                                   maxlength="255">
                            @error('Titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">LE TITRE PRINCIPAL DE VOTRE ANNONCE (MAX 255 CARACTÈRES).</small> {{-- Texte en majuscules --}}
                        </div>

                        {{-- Champ: Description Abrégée --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="DescriptionAbregee" class="form-label fw-bold">DESCRIPTION ABRÉGÉE <span class="accent-text">*</span></label> {{-- Texte en majuscules, gras, et accentué --}}
                            <input type="text"
                                   class="form-control @error('DescriptionAbregee') is-invalid @enderror"
                                   id="DescriptionAbregee"
                                   name="DescriptionAbregee"
                                   value="{{ old('DescriptionAbregee') }}"
                                   placeholder="Ex: Excellent VTT, très peu utilisé."
                                   required
                                   maxlength="100">
                            @error('DescriptionAbregee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">UN RÉSUMÉ CONCIS POUR VOTRE ANNONCE (MAX 100 CARACTÈRES).</small> {{-- Texte en majuscules --}}
                        </div>

                        {{-- Champ: Description Complète --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="DescriptionComplete" class="form-label fw-bold">DESCRIPTION COMPLÈTE</label> {{-- Texte en majuscules, gras --}}
                            <textarea class="form-control @error('DescriptionComplete') is-invalid @enderror"
                                      id="DescriptionComplete"
                                      name="DescriptionComplete"
                                      rows="7" {{-- Augmentation des lignes --}}
                                      placeholder="Décrivez votre article en détail, son état, ses caractéristiques... (Facultatif, mais recommandé)">{{ old('DescriptionComplete') }}</textarea>
                            @error('DescriptionComplete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">FOURNISSEZ TOUS LES DÉTAILS UTILES POUR LES ACHETEURS POTENTIELS.</small> {{-- Texte en majuscules --}}
                        </div>

                        {{-- Champ: Prix --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="Prix" class="form-label fw-bold">PRIX ($)</label> {{-- Texte en majuscules, gras --}}
                            <input type="text"
                                   class="form-control @error('Prix') is-invalid @enderror"
                                   id="Prix"
                                   name="Prix"
                                   value="{{ old('Prix') }}"
                                   placeholder="Ex: 124.59 (Laissez vide si gratuit)"
                                   pattern="^\d+(\.\d{1,2})?$"
                                   title="Veuillez entrer un nombre avec ou sans deux décimales (ex: 124 ou 124.59)">
                            @error('Prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">LAISSEZ VIDE SI L'ARTICLE EST GRATUIT OU À DISCUTER. FORMAT: 123 OU 123.45.</small> {{-- Texte en majuscules --}}
                        </div>

                        {{-- Champ: Date d'expiration --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="DateFin" class="form-label fw-bold">DATE D'EXPIRATION DE L'ANNONCE (OPTIONNEL) :</label> {{-- Texte en majuscules, gras --}}
                            <input type="date"
                                   class="form-control @error('DateFin') is-invalid @enderror"
                                   id="DateFin"
                                   name="DateFin"
                                   value="{{ old('DateFin') }}">
                            @error('DateFin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">LA DATE APRÈS LAQUELLE L'ANNONCE NE SERA PLUS VISIBLE. LAISSEZ VIDE POUR AUCUNE DATE D'EXPIRATION.</small> {{-- Texte en majuscules --}}
                        </div>

                        {{-- Champ: Catégorie --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="Categorie" class="form-label fw-bold">CATÉGORIE <span class="accent-text">*</span></label> {{-- Texte en majuscules, gras, et accentué --}}
                            <select class="form-control @error('Categorie') is-invalid @enderror" id="Categorie" name="Categorie" required>
                                <option value="">-- SÉLECTIONNEZ UNE CATÉGORIE --</option> {{-- Texte en majuscules --}}
                                @forelse($categories as $categorie)
                                    <option value="{{ $categorie->NoCategorie }}" {{ old('Categorie') == $categorie->NoCategorie ? 'selected' : '' }}>
                                        {{ Str::upper($categorie->Description) }} {{-- Description de la catégorie en majuscules --}}
                                    </option>
                                @empty
                                    <option value="" disabled>AUCUNE CATÉGORIE DISPONIBLE</option> {{-- Texte en majuscules --}}
                                @endforelse
                            </select>
                            @error('Categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">CHOISISSEZ LA CATÉGORIE QUI DÉCRIT LE MIEUX VOTRE ANNONCE.</small> {{-- Texte en majuscules --}}
                        </div>

                        {{-- Champ: Photo (nommé photo_annonce dans le contrôleur) --}}
                        <div class="mb-5"> {{-- Marge du bas augmentée pour séparer des boutons --}}
                            <label for="photo_annonce" class="form-label fw-bold">AJOUTER UNE PHOTO (OPTIONNEL)</label> {{-- Texte en majuscules, gras --}}
                            <input type="file"
                                   class="form-control @error('photo_annonce') is-invalid @enderror"
                                   id="photo_annonce"
                                   name="photo_annonce"
                                   accept="image/*">
                            @error('photo_annonce')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">FORMATS ACCEPTÉS : JPG, PNG, GIF, SVG. TAILLE MAXIMALE : 2 MO.</small> {{-- Texte en majuscules --}}
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4"> {{-- Utilisation de gap-3 pour l'espacement et alignement à droite --}}
                            <button type="submit" class="btn custom-primary-button">
                                <i class="fas fa-plus-circle me-2"></i> CRÉER L'ANNONCE
                            </button>
                            <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times-circle me-2"></i> ANNULER
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styles spécifiques pour cette page si non couverts par app.blade.php ou pour les surcharges */

    .card {
        border: 2px solid var(--border-strong) !important;

        box-shadow: var(--shadow-none) !important;
        background-color: var(--light-pure-white);
    }

    .card-header.custom-gradient-header {
        background: var(--primary-dark) !important;

        border-bottom: 2px solid var(--accent-red-orange) !important;
        color: var(--text-on-dark) !important;
        padding: 1.8rem 2.2rem !important;
        display: flex;
        align-items: center;
    }

    .card-header.custom-gradient-header h3 {
        margin-bottom: 0 !important;
        color: inherit !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
    }

    .form-label {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--primary-dark);
        margin-bottom: 0.8rem; /* Plus d'espace sous le label */
    }
    .form-control, .form-select {
         /* Bords carrés */
        border: 2px solid var(--border-strong) !important; /* Bordure plus visible */
        padding: 1rem 1.4rem !important;
        font-size: 1.1rem !important; /* Texte un peu plus grand */
        background-color: var(--light-pure-white) !important;
        color: var(--text-on-light) !important;
    }
    .form-control::placeholder {
        color: var(--placeholder-color) !important;
        opacity: 0.8 !important; /* Légèrement moins opaque */
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--focus-ring-color) !important;
        box-shadow: none !important; /* Pas d'ombre au focus */
    }

    .form-text {
        font-size: 0.9em !important; /* Taille légèrement réduite */
        color: var(--secondary-dark) !important; /* Couleur plus douce */
        margin-top: 0.5rem; /* Marge au-dessus */
        display: block; /* S'assure que cela prend sa propre ligne */
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .custom-primary-button {

        text-transform: uppercase !important;
        font-weight: 700 !important;
        letter-spacing: 0.05em !important;
        padding: 0.85rem 1.8rem !important;
    }

    .btn-outline-secondary {

        text-transform: uppercase !important;
        font-weight: 700 !important;
        padding: 0.85rem 1.8rem !important;
        letter-spacing: 0.04em !important;
        border-width: 2px !important;
        border-color: var(--secondary-dark) !important;
        color: var(--secondary-dark) !important;
    }
    .btn-outline-secondary:hover {
        background-color: var(--secondary-dark) !important;
        color: var(--text-on-dark) !important;
    }
</style>
@endsection
