<?php


namespace App\Events;

use App\Models\Demandes;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class DemandeUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $demande;

    public function __construct(Demandes $demande)
    {
        $this->demande = $demande;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('demande.' . $this->demande->id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->demande->id,
            'statut' => $this->demande->statut,
            'progress' => $this->demande->progress ?? 0,
        ];
    }
}
