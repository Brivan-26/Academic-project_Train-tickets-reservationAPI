<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{   
    protected $table = "reviews";

    use HasFactory;

    protected $fillable = [
        'user_id',
        'travel_id',
        'passenger_name',
        'content',
        'rating'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function travel(){
        return $this->belongsTo(Travel::class, 'travel_id');
    }
}
