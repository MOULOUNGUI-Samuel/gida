<?php

namespace App\Models;
 
use App\Models\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profil extends Model
{
    use HasFactory;
protected $fillable = [
    'user_id',
    'nom',
    'societe',
    'mail',
    'preferences_notif',
    'accessibilite',
    'photo'
];
// HomeController.php

public function index()
    {
        $user = Auth::user();

        // Si tu as défini la relation hasOne dans User
        $profil = $user->profil;

        // S'il n'existe pas encore, on en crée un vide (pour éviter undefined)
        if (!$profil) {
            $profil = new Profil();
        }

        return view('profil.index', data: compact('user', 'profil'));
    }
    
}
