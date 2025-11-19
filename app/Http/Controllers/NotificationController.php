<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demandes;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    
    public function notification_show($id)
    {
    // Récupérer la notification et marquer comme lue
    $notification = Notifications::findOrFail($id);
    // Marquer comme lue
    \DB::table('notifications')
    ->where('id', $id)
    ->update(['read' => true]);

    // Récupérer la demande liée
    $demande = Demandes::findOrFail($notification->id_demande);

    // Appeler la méthode show du DemandesController
    $controller = app(DemandesController::class);
    return $controller->show($demande);

    }


    public function notificationAdmin_show($id)
    {
    // Récupérer la notification
    $notification = \DB::table('notifications')
        ->where('id', $id)
        ->first();

    // Marquer comme lue
    \DB::table('notifications')
    ->where('id', $id)
    ->update(['read' => true]);


    // Rediriger vers la route dashboard
    return redirect()->route('dashboard');
    }
    


}
