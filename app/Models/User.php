<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = ["name", "email", "password", "avatar"];

    protected $hidden = ["password", "remember_token"];

    public function lansias()
    {
        return $this->hasMany(Lansia::class);
    }
}
