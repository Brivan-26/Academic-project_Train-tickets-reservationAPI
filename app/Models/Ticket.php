<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'travel_id',
        'passenger_name',
        'travel_class',
        'payment_method',
        'payment_token',
        'validated',
        'boarding_station',
        'landing_station',
        'price'
    ];
}
