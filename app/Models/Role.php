<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}