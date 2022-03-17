<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Station extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'wilaya'];

    public function travels()
    {
        return $this->belongsToMany(Travel::class);
    }

    public function photos()
    {
        return $this->hasMany(Station_photo::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
