<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Ajouté pour la méthode index

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // La méthode 'create' est moins pertinente si vous utilisez messages.show pour le premier message.
    // Je la laisse telle quelle pour ne pas casser votre code si vous l'utilisez ailleurs.
    public function create(Annonce $annonce)
    {
        if (Auth::id() === $annonce->NoUtilisateur) {
            return redirect()->route('annonces.show', $annonce->NoAnnonce)->with('error', 'Vous ne pouvez pas vous envoyer de message sur votre propre annonce.');
        }

        $sender = Auth::user();
        $receiver = $annonce->user;

        return view('messages.create', compact('annonce', 'sender', 'receiver'));
    }

    /**
     * Stocke un nouveau message dans la base de données.
     * La route passera 'annonce' et 'otherUser'.
     *
     * @param Request $request
     * @param Annonce $annonce L'annonce à laquelle le message est lié.
     * @param User $otherUser L'autre utilisateur (destinataire du message).
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Annonce $annonce, User $otherUser)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $senderId = Auth::id();
        $receiverId = $otherUser->id; // Le destinataire est l'otherUser passé par le Route Model Binding

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

        // Récupérer les identifiants uniques des annonces et des autres utilisateurs impliqués
        // dans les messages de l'utilisateur courant.
        $conversationPartners = Message::select('annonce_id')
            ->selectRaw('CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END AS other_user_id', [$userId])
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->distinct()
            ->get();

        $conversations = collect();

        foreach ($conversationPartners as $partner) {
            $annonceId = $partner->annonce_id;
            $otherUserId = $partner->other_user_id;

            // Récupérer le dernier message de cette conversation spécifique
            $lastMessage = Message::where('annonce_id', $annonceId)
                ->where(function ($query) use ($userId, $otherUserId) {
                    $query->where(function ($q) use ($userId, $otherUserId) {
                        $q->where('sender_id', $userId)
                          ->where('receiver_id', $otherUserId);
                    })->orWhere(function ($q) use ($userId, $otherUserId) {
                        $q->where('sender_id', $otherUserId)
                          ->where('receiver_id', $userId);
                    });
                })
                ->orderByDesc('created_at')
                ->first();

            if ($lastMessage) {
                // Compter les messages non lus pour l'utilisateur courant dans cette conversation
                $unreadCount = Message::where('annonce_id', $annonceId)
                    ->where('receiver_id', $userId)
                    ->where('sender_id', $otherUserId) // Messages envoyés par l'autre utilisateur
                    ->where('read_at_receiver', false)
                    ->count();

                $conversations->push((object) [
                    'annonce' => $lastMessage->annonce,
                    'otherUser' => ($lastMessage->sender_id === $userId) ? $lastMessage->receiver : $lastMessage->sender,
                    'lastMessage' => $lastMessage,
                    'unreadCount' => $unreadCount,
                ]);
            }
        }

        // Trier les conversations par la date du dernier message
        $conversations = $conversations->sortByDesc(function ($conversation) {
            return $conversation->lastMessage->created_at;
        });

        return view('messages.index', compact('conversations'));
    }

    /**
     * Affiche une conversation spécifique entre deux utilisateurs concernant une annonce.
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

        // Vérifier si l'utilisateur courant est bien l'un des participants de cette conversation
        // Si l'utilisateur courant n'est ni l'expéditeur de l'annonce, ni l'otherUser,
        // et qu'aucune conversation n'existe entre eux pour cette annonce, rediriger.
        // On ne vérifie pas 'Annonce->NoUtilisateur' ici directement, mais la présence de messages.
        $hasAccess = Message::where('annonce_id', $annonce->NoAnnonce)
            ->where(function ($query) use ($currentUser, $otherUser) {
                $query->where(function ($q) use ($currentUser, $otherUser) {
                    $q->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $otherUser->id);
                })->orWhere(function ($q) use ($currentUser, $otherUser) {
                    $q->where('sender_id', $otherUser->id)
                      ->where('receiver_id', $currentUser->id);
                });
            })
            ->exists();

        if (!$hasAccess) {
             return redirect()->route('messages.index')->with('error', 'Conversation introuvable ou vous n\'avez pas les droits d\'accès.');
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
            ->with(['sender', 'receiver']) // Eager load sender and receiver for display
            ->get();

        // Marquer comme lus tous les messages reçus par l'utilisateur courant dans cette conversation
        Message::where('annonce_id', $annonce->NoAnnonce)
            ->where('receiver_id', $currentUser->id)
            ->where('sender_id', $otherUser->id) // Uniquement les messages envoyés par l'otherUser
            ->where('read_at_receiver', false)
            ->update(['read_at_receiver' => true]);

        return view('messages.show', compact('messages', 'annonce', 'otherUser'));
    }

    // La méthode 'markAsRead' reste identique, elle est correcte pour marquer un message unique.
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
