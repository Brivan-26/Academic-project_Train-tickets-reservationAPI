<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'password',
        'account_confirmed',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'user_id');
    }
    
    public function support_tickets()
    {
        if ($this->is_passenger()){
            return $this->hasMany(Support_ticket::class);
        }
        if ($this->is_support()){
            return $this->hasMany(Support_ticket::class,'assigned_to');
        }
        return null;
    }

    public function is_admin(){
        return ($this->role->name=="admin");
    }

    public function is_support(){
        return ($this->role->name=="support");
    }

    public function is_passenger(){
        return ($this->role->name=="passenger");
    }

    public function hasAnyRole($roles){
        return $this->role()->whereIn('name', $roles)->first();
    }
}
