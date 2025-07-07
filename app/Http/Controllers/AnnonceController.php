<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\User;
use App\Models\Categorie; // Important : Assurez-vous que ce modèle existe et est correctement importé
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Pour la gestion des images (si vous l'utilisez)
use Illuminate\Support\Str; // Pour Str::limit ou autres fonctions de chaîne si nécessaire

class AnnonceController extends Controller
{
    /**
     * Affiche une liste de toutes les annonces publiques.
     * Accessible par tous (visiteurs et utilisateurs connectés).
     */
    public function index(Request $request)
    {
        // Récupérer toutes les catégories pour le filtre de recherche avancé
        // Assurez-vous que votre modèle Categorie a une colonne 'Description' et 'NoCategorie'
        $categories = Categorie::all();

        // Initialiser la requête pour les annonces actives (Etat = 1)
        $query = Annonce::where('Etat', 1);

        // --- Logique de Filtrage ---

        // Filtrer par description (recherche simple)
        if ($request->filled('Description')) {
            $query->where('DescriptionAbregee', 'like', '%' . $request->Description . '%')
                  ->orWhere('DescriptionComplete', 'like', '%' . $request->Description . '%');
        }

        // Filtrer par auteur (recherche avancée)
        if ($request->filled('Auteur')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->Auteur . '%');
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

        $orderBy = $request->input('TypeOrdre', 'Parution'); // Par défaut, tri par date de parution
        $orderDirection = $request->input('Ordre', 'DESC'); // Par défaut, tri descendant

        // Appliquer le tri
        switch ($orderBy) {
            case 'Parution': // C'est probablement votre colonne de date de création/publication
                $query->orderBy('Parution', $orderDirection);
                break;
            case 'Auteur':
                // Pour trier par le nom de l'utilisateur, nous devons joindre la table 'users'
                // Et utiliser orderBy sur la colonne 'name' de la table 'users'
                $query->join('users', 'annonces.NoUtilisateur', '=', 'users.id')
                      ->select('annonces.*') // Sélectionner toutes les colonnes de l'annonce pour éviter les ambiguïtés
                      ->orderBy('users.name', $orderDirection);
                break;
            case 'Categorie':
                // Pour trier par la description de la catégorie, nous devons joindre la table 'categories'
                $query->join('categories', 'annonces.Categorie', '=', 'categories.NoCategorie')
                      ->select('annonces.*')
                      ->orderBy('categories.Description', $orderDirection);
                break;
            default:
                // Tri par défaut si un TypeOrdre inconnu est fourni
                $query->orderBy('Parution', 'DESC');
                break;
        }

        // --- Pagination ---

        // Récupérer le nombre d'éléments par page depuis la requête (par défaut 10)
        $nbParPage = $request->input('NbParPage', 10);
        // Utiliser la méthode paginate() de Laravel
        $annonces = $query->paginate($nbParPage);


        // Passer les annonces filtrées/triées/paginées ET les catégories à la vue
        return view('ListeAnnonces', compact('annonces', 'categories'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle annonce.
     * Requiert une authentification.
     */
    public function create()
    {
        // Récupérer toutes les catégories pour le formulaire de sélection
        $categories = Categorie::all();
        return view('Annonces.create', compact('categories'));
    }

    /**
     * Enregistre une nouvelle annonce dans la base de données.
     * Requiert une authentification.
     */
    public function store(Request $request)
    {
        $request->validate([
            'DescriptionAbregee' => 'required|string|max:255',
            'DescriptionComplete' => 'nullable|string',
            'Prix' => 'nullable|numeric|min:0',
            'Categorie' => 'required|exists:categories,NoCategorie', // Assurez-vous que la catégorie existe
            'Photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $annonceData = $request->all();
        $annonceData['NoUtilisateur'] = Auth::id(); // L'utilisateur connecté est l'auteur

        if ($request->hasFile('Photo')) {
            $path = $request->file('Photo')->store('photos-annonce', 'public');
            $annonceData['Photo'] = $path;
        }

        // Définir l'état par défaut (par exemple, 1 pour actif)
        $annonceData['Etat'] = 1;
        $annonceData['Parution'] = now(); // Date de parution de l'annonce

        Annonce::create($annonceData);

        return redirect()->route('annonces.list')->with('success', 'Annonce créée avec succès !');
    }

    /**
     * Affiche les détails d'une annonce spécifique.
     * Accessible par tous.
     */
    public function show(Annonce $annonce) // Utilisation de l'injection de modèle Laravel
    {
        return view('Annonces.show', compact('annonce'));
    }

    /**
     * Affiche le formulaire d'édition d'une annonce.
     * Requiert que l'utilisateur soit le propriétaire ou un administrateur.
     */
    public function edit(Annonce $annonce)
    {
        // Vérifier si l'utilisateur est le propriétaire de l'annonce
        if (Auth::id() !== $annonce->NoUtilisateur) {
            abort(403, 'Accès non autorisé.');
        }

        $categories = Categorie::all(); // Pour le formulaire d'édition
        return view('Annonces.edit', compact('annonce', 'categories'));
    }

    /**
     * Met à jour une annonce existante dans la base de données.
     * Requiert que l'utilisateur soit le propriétaire ou un administrateur.
     */
    public function update(Request $request, Annonce $annonce)
    {
        // Vérifier si l'utilisateur est le propriétaire de l'annonce
        if (Auth::id() !== $annonce->NoUtilisateur) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'DescriptionAbregee' => 'required|string|max:255',
            'DescriptionComplete' => 'nullable|string',
            'Prix' => 'nullable|numeric|min:0',
            'Categorie' => 'required|exists:categories,NoCategorie',
            'Photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $annonceData = $request->all();

        if ($request->hasFile('Photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($annonce->Photo && Storage::disk('public')->exists($annonce->Photo)) {
                Storage::disk('public')->delete($annonce->Photo);
            }
            $path = $request->file('Photo')->store('photos-annonce', 'public');
            $annonceData['Photo'] = $path;
        } elseif ($request->boolean('supprimer_photo')) { // Si une case à cocher "supprimer photo" est présente
            if ($annonce->Photo && Storage::disk('public')->exists($annonce->Photo)) {
                Storage::disk('public')->delete($annonce->Photo);
            }
            $annonceData['Photo'] = null;
        }


        $annonce->update($annonceData);

        return redirect()->route('annonces.list')->with('success', 'Annonce mise à jour avec succès !');
    }

    /**
     * Supprime une annonce de la base de données.
     * Requiert que l'utilisateur soit le propriétaire ou un administrateur.
     */
    public function destroy(Annonce $annonce)
    {
        // Vérifier si l'utilisateur est le propriétaire de l'annonce
        if (Auth::id() !== $annonce->NoUtilisateur) {
            abort(403, 'Accès non autorisé.');
        }

        // Supprimer la photo associée si elle existe
        if ($annonce->Photo && Storage::disk('public')->exists($annonce->Photo)) {
            Storage::disk('public')->delete($annonce->Photo);
        }

        $annonce->delete();

        return redirect()->route('annonces.list')->with('success', 'Annonce supprimée avec succès !');
    }

    /**
     * Affiche la liste des annonces de l'utilisateur connecté.
     * Requiert une authentification.
     */
    public function gestionAnnonces()
    {
        $userAnnonces = Auth::user()->annonces()->latest()->get(); // Récupère les annonces de l'utilisateur
        return view('Annonces.gestion', compact('userAnnonces'));
    }
}
