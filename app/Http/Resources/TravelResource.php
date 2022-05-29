<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StationResource as StationResource;
use App\Models\Station;
class TravelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request   
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'departure_station' => new StationResource(Station::find($this->departure_station)),
            'arrival_station' => new StationResource(Station::find($this->arrival_station)),
            'departure_time' => $this->departure_time,
            'distance' => $this->distance,
            'estimated_duration' => $this->estimated_duration,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' =>  $this->updated_at ? $this->updated_at->diffForHumans() : $this->updated_at
        ];
    }
}
