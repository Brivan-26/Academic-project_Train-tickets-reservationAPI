<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_id',
        'firstClass_limitPlaces',
        'secondClass_limitPlaces'
    ];

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }
}
