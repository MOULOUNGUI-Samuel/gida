<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Demandes;
use App\Models\Notifications;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Observers\DemandesObserver;
use Illuminate\Support\Facades\Auth; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    { 
      Demandes::observe(DemandesObserver::class);

      View::composer('*', function ($view) {
    $user = Auth::user(); // récupéré ici, après que l'utilisateur soit connecté
    $nombre_notification_employe = 0;
    $nombre_notification_admin = 0;

    if ($user) {
        // Notifications employé
        $nombre_notification_employe = DB::table('notifications')
            ->join('users', 'notifications.id_user', '=', 'users.id')
            ->join('demandes', 'notifications.id_demande', '=', 'demandes.id')
            ->where('users.fonction', 'Administrateur')
            ->where('demandes.user_id', $user->id)
            ->where('notifications.read', 'false')
            ->count();

        // Notifications admin
        if ($user->fonction === 'Administrateur') {
            $nombre_notification_admin = DB::table('notifications')
                ->join('users', 'notifications.id_user', '=', 'users.id')
                ->where('users.fonction', 'Employe')
                ->where('notifications.read', 'false')
                ->count();
        }
    }

    $view->with(compact('nombre_notification_employe', 'nombre_notification_admin'));
});


   

    }
}
