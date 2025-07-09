<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // N'oubliez pas d'importer la façade Rule

class AnnonceController extends Controller
{
    /**
     * Affiche une liste de toutes les annonces.
     */
    public function index()
    {
        // Modifié pour n'afficher que les annonces qui ne sont pas expirées
        $annonces = Annonce::with(['user', 'categorie'])
                            ->where(function ($query) {
                                $query->whereNull('DateFin') // Annonces sans date de fin
                                      ->orWhere('DateFin', '>=', now()); // Ou annonces dont la date de fin est dans le futur
                            })
                            ->orderByDesc('Parution')
                            ->paginate(10);

        return view('annonces.index', compact('annonces'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle annonce.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour poster une annonce.');
        }

        $categories = Categorie::all();
        return view('annonces.create', compact('categories'));
    }

    /**
     * Stocke une nouvelle annonce dans la base de données.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([ // Utilisation de $validatedData pour récupérer les champs validés
            'Titre' => 'required|string|max:255',
            'DescriptionAbregee' => 'required|string|max:100',
            'DescriptionComplete' => 'required|string',
            'Prix' => 'required|numeric|min:0',
            'Categorie' => ['required', Rule::exists('categories', 'NoCategorie')], // Utilise Rule pour une validation plus robuste
            'photo_annonce' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Nom de l'input du formulaire
            'DateFin' => 'nullable|date|after_or_equal:today', // <<< AJOUTÉ : Nouvelle règle de validation
        ]);

        $imagePath = null;
        if ($request->hasFile('photo_annonce')) {
            $imagePath = $request->file('photo_annonce')->store('annonces_photos', 'public');
        }

        // Utilisation de $validatedData pour la création, en ajoutant les champs non validés directement
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
            'Etat' => 1, // Par exemple, 1 pour 'active'
            'DateFin' => $validatedData['DateFin'] ?? null, // <<< AJOUTÉ : Assignation de la date de fin
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

        $validatedData = $request->validate([ // Utilisation de $validatedData pour récupérer les champs validés
            'Titre' => 'required|string|max:255',
            'DescriptionAbregee' => 'required|string|max:100',
            'DescriptionComplete' => 'required|string',
            'Prix' => 'required|numeric|min:0',
            'Categorie' => ['required', Rule::exists('categories', 'NoCategorie')],
            'photo_annonce' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'DateFin' => 'nullable|date|after_or_equal:today', // <<< AJOUTÉ : Nouvelle règle de validation
            'delete_current_image' => 'boolean', // Permet de gérer la suppression explicite de l'image
        ]);

        $imagePath = $annonce->Photo; // Garde l'ancienne image par défaut
        if ($request->hasFile('photo_annonce')) {
            // Supprime l'ancienne image si elle existe
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

        // Utilisation de $validatedData pour la mise à jour, en ajoutant les champs spécifiques
        $annonce->update([
            'Titre' => $validatedData['Titre'],
            'DescriptionAbregee' => $validatedData['DescriptionAbregee'],
            'DescriptionComplete' => $validatedData['DescriptionComplete'],
            'Prix' => $validatedData['Prix'],
            'Categorie' => $validatedData['Categorie'],
            'Photo' => $imagePath,
            'MiseAJour' => now(), // Met à jour la date de mise à jour
            'DateFin' => $validatedData['DateFin'] ?? null, // <<< AJOUTÉ : Assignation de la date de fin
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

        // Supprime l'image associée si elle existe
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
        // Modifié pour n'afficher que les annonces de l'utilisateur qui ne sont pas expirées dans sa gestion
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
