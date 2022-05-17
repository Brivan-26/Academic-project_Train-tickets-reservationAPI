<?php

namespace App\Http\Resources;

use Egulias\EmailValidator\Result\Reason\DetailedReason;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedTravelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            //'departure_station' => $this->departure_station,
            //'arrival_station' => $this->arrival_station,
            'distance' => $this->distance,
            'estimated_duration' => $this->estimated_duration,
            //'description' => $this->description,
            'status' => $this->status,
            'stations' => DetailedStationResource::collection($this->stations),
            'firstClass_limitPlaces' => $this->classe->firstClass_limitPlaces,
            'secondClass_limitPlaces' => $this->classe->secondClass_limitPlaces,
        ];
    }
}
