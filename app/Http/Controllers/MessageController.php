<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Applique le middleware 'auth' à toutes les méthodes du contrôleur.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche le formulaire pour envoyer un message concernant une annonce spécifique.
     * Cette méthode peut être utile si vous avez un formulaire dédié 'create' pour un premier message.
     * Pour une conversation intégrée sur la page 'show', elle est moins nécessaire.
     * Pour l'instant, laissons-la telle quelle, mais la route 'messages.show' est plus directe.
     *
     * @param Annonce $annonce L'annonce à laquelle le message est lié.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(Annonce $annonce)
    {
        // Empêcher l'utilisateur de s'envoyer un message à lui-même via son annonce
        if (Auth::id() === $annonce->NoUtilisateur) {
            return redirect()->route('annonces.show', $annonce->NoAnnonce)->with('error', 'Vous ne pouvez pas vous envoyer de message sur votre propre annonce.');
        }

        $sender = Auth::user();
        $receiver = $annonce->user;

        // Cette vue 'messages.create' serait pour un formulaire dédié à l'envoi d'un premier message.
        // Si vous utilisez un formulaire directement dans messages.show, cette méthode pourrait être moins utilisée.
        return view('messages.create', compact('annonce', 'sender', 'receiver'));
    }

    /**
     * Stocke un nouveau message dans la base de données.
     * Peut être utilisé pour le premier message ou une réponse.
     *
     * @param Request $request
     * @param Annonce $annonce L'annonce à laquelle le message est lié.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Annonce $annonce)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            // Le receiver_id doit toujours venir du formulaire
            'receiver_id' => 'required|exists:users,id',
        ]);

        $senderId = Auth::id();
        $receiverId = $request->input('receiver_id');

        // Empêcher l'utilisateur de s'envoyer un message à lui-même
        if ((int) $senderId === (int) $receiverId) {
            return back()->with('error', 'Vous ne pouvez pas vous envoyer de message.');
        }

        Message::create([
            'annonce_id' => $annonce->NoAnnonce,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $request->input('content'),
            'read_at_sender' => true, // Le message est lu par l'expéditeur dès l'envoi
            'read_at_receiver' => false, // Le message n'est pas lu par le destinataire par défaut
        ]);

        // Redirige vers la conversation spécifique
        return redirect()->route('messages.show', [
            'annonce' => $annonce->NoAnnonce,
            'otherUser' => $receiverId // Utilise l'ID pour le Route Model Binding automatique dans la route
        ])->with('success', 'Votre message a été envoyé !');
    }

    /**
     * Affiche la liste des conversations (boîte de réception/envoi) de l'utilisateur connecté.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::id();

        // Récupérer les derniers messages uniques par conversation (annonce + participants)
        // Groupement par une clé unique pour chaque conversation
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderBy('created_at', 'desc') // Ordonner d'abord par date pour prendre le plus récent
            ->get()
            ->groupBy(function ($message) use ($userId) {
                // Créer une clé de groupe unique pour chaque conversation
                // (annonce_id + les deux participants, triés pour la cohérence)
                $otherUserId = ($message->sender_id === $userId) ? $message->receiver_id : $message->sender_id;
                return $message->annonce_id . '-' . min($userId, $otherUserId) . '-' . max($userId, $otherUserId);
            })
            ->map(function ($messagesInGroup) {
                // Pour chaque groupe, ne prendre que le message le plus récent
                return $messagesInGroup->first();
            })
            ->sortByDesc('created_at'); // Trier les conversations elles-mêmes par le dernier message

        return view('messages.index', compact('conversations'));
    }

    /**
     * Affiche une conversation spécifique entre deux utilisateurs concernant une annonce.
     * Laravel va automatiquement résoudre $annonce et $otherUser grâce au Route Model Binding
     * si les noms des paramètres de la route correspondent aux noms des variables.
     *
     * @param Annonce $annonce L'annonce concernée par la conversation.
     * @param User $otherUser L'autre utilisateur dans la conversation.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Annonce $annonce, User $otherUser)
    {
        $currentUser = Auth::user();

        // Empêcher l'utilisateur de consulter une conversation avec lui-même
        if ($currentUser->id === $otherUser->id) {
            return redirect()->route('messages.index')->with('error', 'Vous ne pouvez pas consulter une conversation avec vous-même.');
        }

        // Récupérer les messages entre les deux utilisateurs pour cette annonce spécifique
        $messages = Message::where('annonce_id', $annonce->NoAnnonce)
                            ->where(function ($query) use ($currentUser, $otherUser) {
                                $query->where(function ($q) use ($currentUser, $otherUser) {
                                    $q->where('sender_id', $currentUser->id)
                                      ->where('receiver_id', $otherUser->id);
                                })->orWhere(function ($q) use ($currentUser, $otherUser) {
                                    $q->where('sender_id', $otherUser->id)
                                      ->where('receiver_id', $currentUser->id);
                                });
                            })
                            ->orderBy('created_at', 'asc')
                            ->get();

        // Marquer comme lus tous les messages reçus par l'utilisateur courant dans cette conversation
        Message::where('annonce_id', $annonce->NoAnnonce)
                ->where('receiver_id', $currentUser->id)
                ->where('sender_id', $otherUser->id)
                ->where('read_at_receiver', false)
                ->update(['read_at_receiver' => true]);

        return view('messages.show', compact('messages', 'annonce', 'otherUser'));
    }

    /**
     * Marque un message ou une conversation comme lue.
     * Cette méthode semble davantage pertinente pour être appelée via AJAX
     * ou après un clic spécifique sur un message dans la liste.
     * Pour une conversation entière, marquez les messages comme lus dans la méthode 'show'.
     *
     * @param Message $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Message $message)
    {
        $userId = Auth::id();

        if ($userId === $message->receiver_id) {
            $message->update(['read_at_receiver' => true]);
            return back()->with('success', 'Message marqué comme lu.');
        } elseif ($userId === $message->sender_id) {
            // Un expéditeur marque son propre message comme lu (peut-être après relecture)
            $message->update(['read_at_sender' => true]);
            return back()->with('success', 'Votre message envoyé est marqué comme lu.');
        }

        return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
    }
}
