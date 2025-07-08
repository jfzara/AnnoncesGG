<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\User;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Assurez-vous que cette ligne est présente

class AnnonceController extends Controller
{
    /**
     * Affiche une liste de toutes les annonces publiques.
     * Accessible par tous (visiteurs et utilisateurs connectés).
     */
    public function index(Request $request)
    {
        $categories = Categorie::all();
        $query = Annonce::where('Etat', 1);

        // --- Débogage 1 : Voir toutes les entrées de la requête ---
        // dd($request->all());

        // --- Normalisation des termes de recherche ---
        // Utilisation de Str::lower pour passer en minuscules.
        // trim() pour supprimer les espaces en début/fin, preg_replace pour les espaces multiples
        // Str::ascii a été retiré ici pour le débogage.

        $descriptionSearch = $request->input('Description');
        if ($descriptionSearch) {
            $descriptionSearch = Str::lower(trim(preg_replace('/\s+/', ' ', $descriptionSearch)));
        }

        $auteurSearch = $request->input('Auteur');
        if ($auteurSearch) {
            $auteurSearch = Str::lower(trim(preg_replace('/\s+/', ' ', $auteurSearch)));
        }

        // --- Débogage 2 : Voir les termes de recherche APRÈS normalisation ---
        // dd([
        //     'description_recherche_normalisee' => $descriptionSearch,
        //     'auteur_recherche_normalise' => $auteurSearch,
        //     'categorie_selectionnee' => $request->input('Categorie'),
        //     'date_debut_selectionnee' => $request->input('DateDebut'),
        //     'date_fin_selectionnee' => $request->input('DateFin'),
        // ]);


        // --- Logique de Filtrage ---

        // Filtrer par description (recherche simple et permissive)
        if ($descriptionSearch) {
            // --- Débogage 3 : Voir la requête SQL générée pour la description ---
            // (À décommenter si Débogage 2 montre que $descriptionSearch est correct)
            // dd($query->toSql(), $query->getBindings());

            $query->where(function ($q) use ($descriptionSearch) {
                // Utilisation de LOWER pour ignorer la casse.
                // CONVERT(... USING ascii) a été retiré.
                $q->whereRaw('LOWER(DescriptionAbregee) LIKE ?', ['%' . $descriptionSearch . '%'])
                  ->orWhereRaw('LOWER(DescriptionComplete) LIKE ?', ['%' . $descriptionSearch . '%']);
            });
        }

        // Filtrer par auteur (recherche avancée et permissive)
        if ($auteurSearch) {
            // --- Débogage 4 : Voir la requête SQL générée pour l'auteur ---
            // (À décommenter si Débogage 2 montre que $auteurSearch est correct)
            // dd($query->toSql(), $query->getBindings());

            $query->whereHas('user', function ($q) use ($auteurSearch) {
                // Utilisation de LOWER pour ignorer la casse.
                // CONVERT(... USING ascii) a été retiré.
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $auteurSearch . '%']);
            });
        }

        // Filtrer par catégorie (recherche avancée)
        if ($request->filled('Categorie')) {
            $query->where('Categorie', $request->Categorie);
        }

        // Filtrer par date de début (recherche avancée)
        if ($request->filled('DateDebut')) {
            $query->where('Parution', '>=', $request->DateDebut . ' 00:00:00');
        }

        // Filtrer par date de fin (recherche avancée)
        if ($request->filled('DateFin')) {
            $query->where('Parution', '<=', $request->DateFin . ' 23:59:59');
        }

        // --- Logique de Tri ---
        $orderBy = $request->input('TypeOrdre', 'Parution');
        $orderDirection = $request->input('Ordre', 'DESC');

        switch ($orderBy) {
            case 'Parution':
                $query->orderBy('Parution', $orderDirection);
                break;
            case 'Auteur':
                $query->join('users', 'annonces.NoUtilisateur', '=', 'users.id')
                      ->select('annonces.*')
                      ->orderBy('users.name', $orderDirection);
                break;
            case 'Categorie':
                $query->join('categories', 'annonces.Categorie', '=', 'categories.NoCategorie')
                      ->select('annonces.*')
                      ->orderBy('categories.Description', $orderDirection);
                break;
            default:
                $query->orderBy('Parution', 'DESC');
                break;
        }

        // --- Débogage 5 : Voir la requête SQL FINALE et les bindings avant exécution ---
        // dd($query->toSql(), $query->getBindings());

        // --- Pagination ---
        $nbParPage = $request->input('NbParPage', 10);
        $annonces = $query->paginate($nbParPage);

        // --- Débogage 6 : Voir les annonces obtenues APRÈS la requête ---
        // dd($annonces);

        return view('ListeAnnonces', compact('annonces', 'categories'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle annonce.
     * Requiert une authentification.
     */
    public function create()
    {
        $categories = Categorie::all();
        return view('annonces.create', compact('categories'));
    }

    /**
     * Enregistre une nouvelle annonce dans la base de données.
     * Requiert une authentification.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'DescriptionAbregee' => 'required|string|min:3|max:100',
            'DescriptionComplete' => 'nullable|string|max:5000',
            'Prix' => 'nullable|numeric|min:0.01|max:9999999.99',
            'Categorie' => 'required|exists:categories,NoCategorie',
            'Photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'DescriptionAbregee.required' => 'Le titre de votre annonce est obligatoire.',
            'DescriptionAbregee.string' => 'Le titre doit être une chaîne de caractères.',
            'DescriptionAbregee.min' => 'Le titre doit comporter au moins :min caractères.',
            'DescriptionAbregee.max' => 'Le titre ne peut pas dépasser :max caractères.',
            'DescriptionComplete.string' => 'La description complète doit être une chaîne de caractères.',
            'DescriptionComplete.max' => 'La description complète ne peut pas dépasser :max caractères.',
            'Prix.numeric' => 'Le prix doit être un nombre valide (ex: 12.99).',
            'Prix.min' => 'Le prix doit être supérieur à zéro si spécifié.',
            'Prix.max' => 'Le prix ne peut pas dépasser :max$.',
            'Categorie.required' => 'Veuillez sélectionner une catégorie pour votre annonce.',
            'Categorie.exists' => 'La catégorie sélectionnée n\'est pas valide.',
            'Photo.image' => 'Le fichier doit être une image (ex: JPG, PNG).',
            'Photo.mimes' => 'Les formats d\'image acceptés sont JPEG, PNG, JPG, GIF et SVG.',
            'Photo.max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
        ]);

        $annonceData = $request->all();
        $annonceData['NoUtilisateur'] = Auth::id();

        if ($request->hasFile('Photo')) {
            $path = $request->file('Photo')->store('photos-annonce', 'public');
            $annonceData['Photo'] = $path;
        } else {
            $annonceData['Photo'] = null;
        }

        $annonceData['Etat'] = 1;
        $annonceData['Parution'] = now();

        Annonce::create($annonceData);

        return redirect()->route('annonces.index')->with('success', 'Félicitations ! Votre annonce a été créée avec succès et est maintenant en ligne.');
    }

    /**
     * Affiche les détails d'une annonce spécifique.
     * Accessible par tous.
     */
    public function show(Annonce $annonce)
    {
        return view('annonces.show', compact('annonce'));
    }

    /**
     * Affiche le formulaire d'édition d'une annonce.
     * Requiert que l'utilisateur soit le propriétaire ou un administrateur.
     */
    public function edit(Annonce $annonce)
    {
        if (Auth::id() !== $annonce->NoUtilisateur) {
            abort(403, 'Accès non autorisé.');
        }

        $categories = Categorie::all();
        return view('annonces.edit', compact('annonce', 'categories'));
    }

    /**
     * Met à jour une annonce existante dans la base de données.
     * Requiert que l'utilisateur soit le propriétaire ou un administrateur.
     */
    public function update(Request $request, Annonce $annonce)
    {
        if (Auth::id() !== $annonce->NoUtilisateur) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'DescriptionAbregee' => 'required|string|min:3|max:100',
            'DescriptionComplete' => 'nullable|string|max:5000',
            'Prix' => 'nullable|numeric|min:0.01|max:9999999.99',
            'Categorie' => 'required|exists:categories,NoCategorie',
            'Photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'DescriptionAbregee.required' => 'Le titre de votre annonce est obligatoire.',
            'DescriptionAbregee.string' => 'Le titre doit être une chaîne de caractères.',
            'DescriptionAbregee.min' => 'Le titre doit comporter au moins :min caractères.',
            'DescriptionAbregee.max' => 'Le titre ne peut pas dépasser :max caractères.',
            'DescriptionComplete.string' => 'La description complète doit être une chaîne de caractères.',
            'DescriptionComplete.max' => 'La description complète ne peut pas dépasser :max caractères.',
            'Prix.numeric' => 'Le prix doit être un nombre valide (ex: 12.99).',
            'Prix.min' => 'Le prix doit être supérieur à zéro si spécifié.',
            'Prix.max' => 'Le prix ne peut pas dépasser :max$.',
            'Categorie.required' => 'Veuillez sélectionner une catégorie pour votre annonce.',
            'Categorie.exists' => 'La catégorie sélectionnée n\'est pas valide.',
            'Photo.image' => 'Le fichier doit être une image (ex: JPG, PNG).',
            'Photo.mimes' => 'Les formats d\'image acceptés sont JPEG, PNG, JPG, GIF et SVG.',
            'Photo.max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
        ]);

        $annonceData = $request->all();

        if ($request->hasFile('Photo')) {
            if ($annonce->Photo && Storage::disk('public')->exists($annonce->Photo)) {
                Storage::disk('public')->delete($annonce->Photo);
            }
            $path = $request->file('Photo')->store('photos-annonce', 'public');
            $annonceData['Photo'] = $path;
        } elseif ($request->boolean('supprimer_photo')) {
            if ($annonce->Photo && Storage::disk('public')->exists($annonce->Photo)) {
                Storage::disk('public')->delete($annonce->Photo);
            }
            $annonceData['Photo'] = null;
        } else {
            unset($annonceData['Photo']);
        }

        $annonce->update($annonceData);

        return redirect()->route('annonces.index')->with('success', 'Annonce mise à jour avec succès !');
    }

    /**
     * Supprime une annonce de la base de données.
     * Requiert que l'utilisateur soit le propriétaire ou un administrateur.
     */
    public function destroy(Annonce $annonce)
    {
        if (Auth::id() !== $annonce->NoUtilisateur) {
            abort(403, 'Accès non autorisé.');
        }

        if ($annonce->Photo && Storage::disk('public')->exists($annonce->Photo)) {
            Storage::disk('public')->delete($annonce->Photo);
        }

        $annonce->delete();

        return redirect()->route('annonces.index')->with('success', 'Annonce supprimée avec succès !');
    }

    /**
     * Affiche la liste des annonces de l'utilisateur connecté.
     * Requiert une authentification.
     */
    public function gestionAnnonces()
    {
        $userannonces = Auth::user()->annonces()->latest()->get();
        return view('annonces.gestion', compact('userannonces'));
    }
}
