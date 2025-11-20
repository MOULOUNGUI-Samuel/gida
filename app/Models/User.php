<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'username',
        'code_entreprise',
        'matricule',
        'password',
        'type',
        'photo',
        'societe',
        'email',
        'entreprise_id',
        'nedcore_user_id',
        'fonction',
        'fonction_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the entreprise that owns the user.
     */
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    /**
     * Get the fonction that owns the user.
     */
    public function fonction()
    {
        return $this->belongsTo(Fonction::class, 'fonction_id');
    }

    public function profil()
    {
        return $this->hasOne(Profil::class);
    }

    public function demandes()
    {
        return $this->hasMany(Demandes::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


}