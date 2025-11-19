<?php

namespace App\Observers;

use App\Models\Demandes;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

        // 🔹 Récupérer tous les admins
        $admins = DB::table('users')
                    ->where('fonction', 'Administrateur')
                    ->get();

        // 🔹 Temporairement désactivé l'envoi d'emails en attendant la configuration SMTP
        // foreach ($admins as $admin) {
        //     if ($admin->email) {
        //         try {
        //             Mail::raw($notification->message, function ($message) use ($admin, $notification) {
        //                 $message->to($admin->email)
        //                         ->subject($notification->type_notification);
        //             });
        //         } catch (\Exception $e) {
        //             \Log::warning("Erreur d'envoi d'email à {$admin->email}: " . $e->getMessage());
        //         }
        //     }
        // }
    }

    /**
     * Handle the Demande "updated" event.
     */
    public function updated(Demandes $demande)
    {
        $user = Auth::user();
        $destinataire = DB::table('users')->where('id', $demande->user_id)->first();

        // 🔹 Notification si societe_assignee a changé
      

        // 🔹 Notification si la priorité a changé
       

        // 🔹 Notification si le statut a changé
        if ($demande->isDirty('statut')) {
            $notification = Notifications::create([
                'id_demande'        => $demande->id,
                'id_user'           => $user->id,
                'message'           => "Le statut de votre demande est à présent : {$demande->statut}",
                'type_notification' => 'Changement du statut de votre demande',
                'read'              => false,
            ]);

            if ($destinataire && $destinataire->email) {
                Mail::raw($notification->message, function ($message) use ($destinataire, $notification) {
                    $message->to($destinataire->email)
                            ->subject($notification->type_notification);
                });
            }
        
        }
    }
}
