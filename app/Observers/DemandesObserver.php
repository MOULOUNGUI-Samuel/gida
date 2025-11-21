<?php

namespace App\Observers;

use App\Models\Demandes;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DemandesObserver
{
    /**
     * Handle the Demande "created" event.
     */
    public function created(Demandes $demande)
    {
        $user = Auth::user(); // récupère l'utilisateur connecté

        // 🔹 Nouvelle notification
        $notification = Notifications::create([
            'id_demande'        => $demande->id,
            'id_user'           => $user->id,
            'message'           => "Une nouvelle demande d'assistance a été envoyée par {$demande->nom}",
            'type_notification' => 'Nouvelle demande',
            'read'              => false,
        ]);

        // Note: L'envoi d'emails a été désactivé
    }

    /**
     * Handle the Demande "updated" event.
     */
    public function updated(Demandes $demande)
    {
        $user = Auth::user();

        // 🔹 Notification si le statut a changé
        if ($demande->isDirty('statut')) {
            Notifications::create([
                'id_demande'        => $demande->id,
                'id_user'           => $user->id,
                'message'           => "Le statut de votre demande est à présent : {$demande->statut}",
                'type_notification' => 'Changement du statut de votre demande',
                'read'              => false,
            ]);
        }
    }
}
