
Broadcast::channel('demande.{id}', function ($user, $id) {
    $demande = \App\Models\Demandes::find($id);
    return $demande && $demande->user_id === $user->id; 
    // autoriser seulement le demandeur (et éventuellement les admins)
});
