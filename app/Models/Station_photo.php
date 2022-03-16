<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station_photo extends Model
{
    use HasFactory;

    protected $fillable = ['station_id', 'photo_url'];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
