@extends('layouts.app')

@section('title', 'MODIFIER L\'ANNONCE - ' . Str::upper($annonce->Titre)) {{-- Titre en majuscules et accentué --}}

@section('content')
<div class="container mt-4 mb-5"> {{-- Ajout de mb-5 pour plus d'espace en bas --}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card"> {{-- Suppression de shadow-sm et border-0 pour utiliser nos styles via CSS --}}
                <div class="card-header custom-gradient-header d-flex align-items-center"> {{-- Ajout de d-flex et align-items-center --}}
                    <i class="fas fa-edit fa-lg me-3 text-white"></i> {{-- Icône en blanc --}}
                    <h3 class="mb-0 text-white">MODIFIER L'ANNONCE : <span class="accent-text">{{ Str::upper($annonce->DescriptionAbregee) }}</span></h3> {{-- Titre en majuscules, le span "yellow-text" devient "accent-text" --}}
                </div>

                <div class="card-body p-4"> {{-- Augmentation du padding interne pour plus d'aération --}}
                    <form method="POST" action="{{ route('annonces.update', $annonce->NoAnnonce) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Champ Titre --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="Titre" class="form-label fw-bold">TITRE DE L'ANNONCE <span class="accent-text">*</span></label> {{-- Texte en majuscules, gras, et accentué --}}
                            <input type="text" class="form-control @error('Titre') is-invalid @enderror" id="Titre" name="Titre" value="{{ old('Titre', $annonce->Titre) }}" required placeholder="TITRE DE VOTRE ANNONCE">
                            @error('Titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">LE TITRE PRINCIPAL DE VOTRE ANNONCE (MAX 255 CARACTÈRES).</small>
                        </div>

                        {{-- Champ Description Abrégée --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="DescriptionAbregee" class="form-label fw-bold">DESCRIPTION ABRÉGÉE <span class="accent-text">*</span></label>
                            <input type="text" class="form-control @error('DescriptionAbregee') is-invalid @enderror" id="DescriptionAbregee" name="DescriptionAbregee" value="{{ old('DescriptionAbregee', $annonce->DescriptionAbregee) }}" required placeholder="BREF RÉSUMÉ DE VOTRE ANNONCE">
                            @error('DescriptionAbregee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">UN RÉSUMÉ CONCIS POUR VOTRE ANNONCE (MAX 100 CARACTÈRES).</small>
                        </div>

                        {{-- Champ Description Complète --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="DescriptionComplete" class="form-label fw-bold">DESCRIPTION COMPLÈTE</label>
                            <textarea class="form-control @error('DescriptionComplete') is-invalid @enderror" id="DescriptionComplete" name="DescriptionComplete" rows="7" placeholder="DESCRIPTION DÉTAILLÉE DE VOTRE ARTICLE">{{ old('DescriptionComplete', $annonce->DescriptionComplete) }}</textarea>
                            @error('DescriptionComplete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">FOURNISSEZ TOUS LES DÉTAILS UTILES POUR LES ACHETEURS POTENTIELS.</small>
                        </div>

                        {{-- Champ Prix --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="Prix" class="form-label fw-bold">PRIX ($)</label>
                            <input type="text"
                                   class="form-control @error('Prix') is-invalid @enderror"
                                   id="Prix"
                                   name="Prix"
                                   value="{{ old('Prix', $annonce->Prix) }}"
                                   placeholder="EX: 124.59 (LAISSEZ VIDE SI GRATUIT)"
                                   pattern="^\d+(\.\d{1,2})?$"
                                   title="VEUILLEZ ENTRER UN NOMBRE AVEC OU SANS DEUX DÉCIMALES (EX: 124 OU 124.59)">
                            @error('Prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">LAISSEZ VIDE SI L'ARTICLE EST GRATUIT OU À DISCUTER. FORMAT: 123 OU 123.45.</small>
                        </div>

                        {{-- Champ DateFin --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="DateFin" class="form-label fw-bold">DATE D'EXPIRATION DE L'ANNONCE (OPTIONNEL) :</label>
                            <input type="date"
                                   class="form-control @error('DateFin') is-invalid @enderror"
                                   id="DateFin"
                                   name="DateFin"
                                   value="{{ old('DateFin', $annonce->DateFin ? $annonce->DateFin->format('Y-m-d') : '') }}">
                            @error('DateFin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">LA DATE APRÈS LAQUELLE L'ANNONCE NE SERA PLUS VISIBLE. LAISSEZ VIDE POUR AUCUNE DATE D'EXPIRATION.</small>
                        </div>

                        {{-- Champ Catégorie --}}
                        <div class="mb-4"> {{-- Marge du bas augmentée --}}
                            <label for="Categorie" class="form-label fw-bold">CATÉGORIE <span class="accent-text">*</span></label>
                            <select class="form-select @error('Categorie') is-invalid @enderror" id="Categorie" name="Categorie" required>
                                <option value="">-- SÉLECTIONNEZ UNE CATÉGORIE --</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->NoCategorie }}" {{ old('Categorie', $annonce->NoCategorie) == $categorie->NoCategorie ? 'selected' : '' }}>
                                        {{ Str::upper($categorie->Description) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">CHOISISSEZ LA CATÉGORIE QUI DÉCRIT LE MIEUX VOTRE ANNONCE.</small>
                        </div>

                        {{-- Champ Photo (gestion de l'upload et affichage de l'image existante) --}}
                        <div class="mb-5"> {{-- Marge du bas augmentée pour séparer des boutons --}}
                            <label for="photo_annonce" class="form-label fw-bold">PHOTO DE L'ANNONCE</label>
                            <input type="file" class="form-control @error('photo_annonce') is-invalid @enderror" id="photo_annonce" name="photo_annonce" accept="image/*">
                            @error('photo_annonce')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if($annonce->Photo)
                                <div class="mt-4 p-3 d-flex align-items-center" style="border: 2px dashed var(--border-subtle); background-color: var(--light-grey);"> {{-- Cadre pour la photo actuelle --}}
                                    <img src="{{ asset('storage/' . $annonce->Photo) }}" alt="Photo actuelle de l'annonce" class="img-fluid me-4" style="max-width: 150px; height: auto; border: 2px solid var(--border-strong); border-radius: 0;"> {{-- Image plus grande, sans arrondi, avec bordure --}}
                                    <div>
                                        <p class="fw-bold mb-2 text-uppercase" style="color: var(--primary-dark);">PHOTO ACTUELLE :</p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="delete_current_image" id="delete_current_image" value="1">
                                            <label class="form-check-label text-muted text-uppercase" for="delete_current_image">
                                                SUPPRIMER LA PHOTO ACTUELLE
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <small class="form-text text-muted mt-2">FORMATS ACCEPTÉS : JPG, PNG, GIF, SVG. TAILLE MAXIMALE : 2 MO.</small>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4"> {{-- Utilisation de gap-3 pour l'espacement et alignement à droite --}}
                            <button type="submit" class="btn custom-primary-button">
                                <i class="fas fa-save me-2"></i> METTRE À JOUR L'ANNONCE
                            </button>
                            <a href="{{ route('annonces.gestion') }}" class="btn btn-outline-secondary">
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

    /* Styles pour la photo actuelle */
    .form-check-input {
         /* Carré pour la checkbox */
        border: 2px solid var(--border-strong) !important;
    }
    .form-check-label {
        color: var(--secondary-dark) !important;
        text-transform: uppercase;
        font-size: 0.9em;
    }
</style>
@endsection
