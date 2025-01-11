<?php

namespace App\Models;

use App\Concerns\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Admin extends User
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'username', 'phone_number', 'password', 'super_admin', 'status',];
}
