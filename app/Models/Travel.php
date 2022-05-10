<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    protected $table = 'travels';
    use HasFactory;

    protected $fillable = [
        'departure_station',
        'departure_time',
        'arrival_station',
        'distance',
        'estimated_duration',
        'description',
        'status'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'travel_id');
    }
    
    public function stations()
    {
        return $this->belongsToMany(Station::class);
    }

    public function classe()
    {
        return $this->hasOne(Classe::class);
    }
}
