<?php
namespace App\Http\Repositories;

use App\Models\Station;
class StationRepository {

    public function all()
    {
        $stations = Station::all();
        return $stations;
    }
}
