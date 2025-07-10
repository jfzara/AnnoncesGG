<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AnnonceController extends Controller
{
    /**
     * Affiche une liste de toutes les annonces avec des options de recherche/filtre.
     */
    public function index(Request $request)
    {
        // Récupérer les termes de recherche et de filtre de la requête
        $search = $request->query('search');
        $category = $request->query('category');
        // $sort = $request->query('sort'); // Pour le tri, si on l'ajoute plus tard

        // Commencer la requête de base pour les annonces non expirées
        $annoncesQuery = Annonce::with(['user', 'categorie'])
                                ->where(function ($query) {
                                    $query->whereNull('DateFin')
                                          ->orWhere('DateFin', '>=', now());
                                });

        // Appliquer le filtre de recherche par titre/description
        if ($search) {
            $annoncesQuery->where(function($query) use ($search) {
                $query->where('Titre', 'like', '%' . $search . '%')
                      ->orWhere('DescriptionAbregee', 'like', '%' . $search . '%')
                      ->orWhere('DescriptionComplete', 'like', '%' . $search . '%');
            });
        }

        // Appliquer le filtre par catégorie
        if ($category && $category !== 'all') {
            $annoncesQuery->where('Categorie', $category);
        }

        // Appliquer le tri par défaut (les plus récentes en premier)
        $annoncesQuery->orderByDesc('Parution');

        // Obtenir les annonces paginées
        $annonces = $annoncesQuery->paginate(10)->withQueryString(); // withQueryString() pour conserver les paramètres de filtre lors de la pagination

        // Charger toutes les catégories pour le filtre déroulant
        $categories = Categorie::all();

        // Passer les annonces, les catégories, et les paramètres de recherche/filtre à la vue
        return view('annonces.index', compact('annonces', 'categories', 'search', 'category'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle annonce.
     */
    public function create()
    {
        // Vérification de l'authentification (gérée aussi par le middleware 'auth' sur la route)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour poster une annonce.');
        }

        // Chargement de toutes les catégories pour le formulaire
        $categories = Categorie::all();

        // Retourne la vue de création d'annonce avec les catégories
        return view('annonces.create', compact('categories'));
    }

    /**
     * Stocke une nouvelle annonce dans la base de données.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Titre' => 'required|string|max:255',
            'DescriptionAbregee' => 'required|string|max:100',
            'DescriptionComplete' => 'required|string',
            'Prix' => 'nullable|numeric|min:0', // Rendre le prix nullable pour les champs optionnels
            'Categorie' => ['required', Rule::exists('categories', 'NoCategorie')],
            'photo_annonce' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'DateFin' => 'nullable|date|after_or_equal:today',
        ]);

        $imagePath = null;
        if ($request->hasFile('photo_annonce')) {
            $imagePath = $request->file('photo_annonce')->store('annonces_photos', 'public');
        }

        Annonce::create([
            'NoUtilisateur' => Auth::id(),
            'Parution' => now(),
            'Categorie' => $validatedData['Categorie'],
            'Titre' => $validatedData['Titre'],
            'DescriptionAbregee' => $validatedData['DescriptionAbregee'],
            'DescriptionComplete' => $validatedData['DescriptionComplete'],
            'Prix' => $validatedData['Prix'],
            'Photo' => $imagePath,
            'MiseAJour' => now(),
            'Etat' => 1,
            'DateFin' => $validatedData['DateFin'] ?? null,
        ]);

        return redirect()->route('annonces.index')->with('success', 'Annonce créée avec succès !');
    }

    /**
     * Affiche les détails d'une annonce spécifique.
     */
    public function show(Annonce $annonce)
    {
        $annonce->load('user', 'categorie');
        return view('annonces.show', compact('annonce'));
    }

    /**
     * Affiche le formulaire d'édition pour une annonce spécifique.
     */
    public function edit(Annonce $annonce)
    {
        if (Auth::id() !== $annonce->NoUtilisateur) {
            return redirect()->route('annonces.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette annonce.');
        }

        $categories = Categorie::all();
        return view('annonces.edit', compact('annonce', 'categories'));
    }

    /**
     * Met à jour une annonce spécifique dans la base de données.
     */
    public function update(Request $request, Annonce $annonce)
    {
        if (Auth::id() !== $annonce->NoUtilisateur) {
            return redirect()->route('annonces.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette annonce.');
        }

        $validatedData = $request->validate([
            'Titre' => 'required|string|max:255',
            'DescriptionAbregee' => 'required|string|max:100',
            'DescriptionComplete' => 'required|string',
            'Prix' => 'nullable|numeric|min:0', // Rendre le prix nullable
            'Categorie' => ['required', Rule::exists('categories', 'NoCategorie')],
            'photo_annonce' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'DateFin' => 'nullable|date|after_or_equal:today',
            'delete_current_image' => 'boolean',
        ]);

        $imagePath = $annonce->Photo;
        if ($request->hasFile('photo_annonce')) {
            if ($annonce->Photo) {
                Storage::disk('public')->delete($annonce->Photo);
            }
            $imagePath = $request->file('photo_annonce')->store('annonces_photos', 'public');
        } elseif (isset($validatedData['delete_current_image']) && $validatedData['delete_current_image']) {
            if ($annonce->Photo) {
                Storage::disk('public')->delete($annonce->Photo);
                $imagePath = null;
            }
        }

        $annonce->update([
            'Titre' => $validatedData['Titre'],
            'DescriptionAbregee' => $validatedData['DescriptionAbregee'],
            'DescriptionComplete' => $validatedData['DescriptionComplete'],
            'Prix' => $validatedData['Prix'],
            'Categorie' => $validatedData['Categorie'],
            'Photo' => $imagePath,
            'MiseAJour' => now(),
            'Etat' => 1,
            'DateFin' => $validatedData['DateFin'] ?? null,
        ]);

        return redirect()->route('annonces.show', $annonce->NoAnnonce)->with('success', 'Annonce mise à jour avec succès !');
    }

    /**
     * Supprime une annonce de la base de données.
     */
    public function destroy(Annonce $annonce)
    {
        if (Auth::id() !== $annonce->NoUtilisateur) {
            return redirect()->route('annonces.index')->with('error', 'Vous n\'êtes pas autorisé à supprimer cette annonce.');
        }

        if ($annonce->Photo) {
            Storage::disk('public')->delete($annonce->Photo);
        }

        $annonce->delete();
        return redirect()->route('annonces.gestion')->with('success', 'Annonce supprimée avec succès !');
    }

    /**
     * Affiche les annonces de l'utilisateur connecté pour la gestion.
     */
    public function gestionAnnonces()
    {
        $annonces = Auth::user()->annonces()
                            ->with('categorie')
                            ->where(function ($query) {
                                $query->whereNull('DateFin')
                                      ->orWhere('DateFin', '>=', now());
                            })
                            ->orderByDesc('Parution')
                            ->paginate(10);

        return view('annonces.gestion', compact('annonces'));
    }
}
