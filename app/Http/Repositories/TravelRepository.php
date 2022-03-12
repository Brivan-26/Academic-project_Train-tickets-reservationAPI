<?php

namespace App\Http\Repositories;

use App\Models\Travel;
Class TravelRepository
{
    public function all()
    {
        return Travel::all();
    }

    
}