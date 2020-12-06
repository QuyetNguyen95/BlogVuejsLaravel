<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'fullName',
        'email',
        'password',
        'role_id'
    ];
    //1 user has many role
    public function role(){
        return $this->belongsTo('App\Models\Role','role_id');
    }
}
