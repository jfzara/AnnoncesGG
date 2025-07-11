<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Pas directement utilisé dans cette version, mais utile si besoin
use Illuminate\Support\Facades\Log; // Ajouté pour le débogage si nécessaire

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Annonce $annonce)
    {
        if (Auth::id() === $annonce->NoUtilisateur) {
            return redirect()->route('annonces.show', $annonce->NoAnnonce)->with('error', 'Vous ne pouvez pas vous envoyer de message sur votre propre annonce.');
        }

        $sender = Auth::user();
        $receiver = $annonce->user;

        return view('messages.create', compact('annonce', 'sender', 'receiver'));
    }

    public function store(Request $request, Annonce $annonce, User $otherUser)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $senderId = Auth::id();
        $receiverId = $otherUser->id;

        if ((int) $senderId === (int) $receiverId) {
            return back()->with('error', 'Vous ne pouvez pas vous envoyer de message.');
        }

        Message::create([
            'annonce_id' => $annonce->NoAnnonce,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $request->input('content'),
            'read_at_sender' => true,
            'read_at_receiver' => false,
        ]);

        return redirect()->route('messages.show', [
            'annonce' => $annonce->NoAnnonce,
            'otherUser' => $receiverId
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

        // Récupérer tous les messages où l'utilisateur est l'expéditeur ou le destinataire,
        // et charger les relations sender, receiver et annonce pour éviter les N+1 queries.
        $allMessages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver', 'annonce'])
            ->orderBy('created_at', 'desc')
            ->get();

        $conversations = $allMessages->groupBy(function ($message) use ($userId) {
            // Créer une clé unique pour chaque conversation, indépendante de l'ordre des participants.
            // Ceci assure que les messages entre A et B sur l'annonce X sont regroupés ensemble,
            // que A soit sender/B receiver ou B sender/A receiver.
            $participantIds = collect([$message->sender_id, $message->receiver_id])->sort()->implode('-');
            return $message->annonce_id . '-' . $participantIds;
        })->map(function ($messagesInConversation) use ($userId) {
            // Pour chaque groupe (conversation), prendre le dernier message pour les informations de base
            $lastMessage = $messagesInConversation->first(); // Premier après le orderByDesc, donc le plus récent

            // Déterminer l'autre participant
            $otherUser = ($lastMessage->sender_id === $userId) ? $lastMessage->receiver : $lastMessage->sender;

            // Compter les messages non lus pour l'utilisateur courant dans cette conversation
            $unreadCount = $messagesInConversation
                ->where('receiver_id', $userId)
                ->where('read_at_receiver', false)
                ->count();

            // Retourner un objet structuré pour la vue
            return (object) [
                'annonce' => $lastMessage->annonce,
                'otherUser' => $otherUser,
                'lastMessage' => $lastMessage, // Contient le message le plus récent de la conversation
                'unreadCount' => $unreadCount,
            ];
        })->sortByDesc(function ($conversation) {
            // Trier les conversations par la date du dernier message
            return $conversation->lastMessage->created_at;
        });

        return view('messages.index', compact('conversations'));
    }


    /**
     * Affiche une conversation spécifique entre deux utilisateurs concernant une annonce.
     * Cette méthode est également utilisée pour initier une nouvelle conversation.
     *
     * @param Annonce $annonce L'annonce concernée par la conversation.
     * @param User $otherUser L'autre utilisateur dans la conversation (l'annonceur ou la personne contactée).
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Annonce $annonce, User $otherUser)
    {
        $currentUser = Auth::user();

        // 1. Empêcher l'utilisateur de consulter une conversation avec lui-même
        if ($currentUser->id === $otherUser->id) {
            return redirect()->route('messages.index')->with('error', 'Vous ne pouvez pas consulter une conversation avec vous-même.');
        }

        // 2. Vérification d'accès : l'utilisateur courant doit être l'un des deux participants de la conversation.
        // Les deux participants sont l'utilisateur connecté ($currentUser) et l'autre utilisateur ($otherUser, qui est l'annonceur).
        // Si l'utilisateur courant n'est ni l'annonceur, ni la personne qui contacte l'annonceur, alors il n'a pas accès.
        // Cette condition est cruciale pour éviter qu'un utilisateur C ne puisse voir une conversation entre A et B.
        $isLegitimateParticipant = (
            ($currentUser->id === $annonce->NoUtilisateur && $otherUser->id !== $currentUser->id) || // L'utilisateur est l'annonceur et l'autre est un contact
            ($currentUser->id !== $annonce->NoUtilisateur && $otherUser->id === $annonce->NoUtilisateur) // L'utilisateur est le contact et l'autre est l'annonceur
        );

        if (!$isLegitimateParticipant) {
             // Log::warning("Accès non autorisé à la conversation. User: {$currentUser->id}, Annonce: {$annonce->NoAnnonce}, OtherUser: {$otherUser->id}");
             return redirect()->route('messages.index')->with('error', 'Vous n\'avez pas les droits d\'accès à cette conversation.');
        }


        // Récupérer tous les messages entre les deux utilisateurs pour cette annonce spécifique.
        // Si aucun message n'existe encore, la collection sera vide, ce qui est attendu pour une nouvelle conversation.
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
            ->with(['sender', 'receiver', 'annonce']) // Assurez-vous que les relations sont chargées
            ->get();

        // Marquer comme lus tous les messages reçus par l'utilisateur courant
        // qui proviennent de l'otherUser pour cette annonce spécifique.
        Message::where('annonce_id', $annonce->NoAnnonce)
            ->where('receiver_id', $currentUser->id)
            ->where('sender_id', $otherUser->id)
            ->where('read_at_receiver', false)
            ->update(['read_at_receiver' => true]);

        return view('messages.show', compact('messages', 'annonce', 'otherUser'));
    }

    public function markAsRead(Message $message)
    {
        $userId = Auth::id();

        if ($userId === $message->receiver_id) {
            $message->update(['read_at_receiver' => true]);
            return back()->with('success', 'Message marqué comme lu.');
        } elseif ($userId === $message->sender_id) {
            $message->update(['read_at_sender' => true]);
            return back()->with('success', 'Votre message envoyé est marqué comme lu.');
        }

        return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
    }
}
