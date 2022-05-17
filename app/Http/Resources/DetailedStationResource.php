<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailedStationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'wilaya' => $this->wilaya,
            'arrival_time' => $this->pivot->arrival_time,
            'firstClass_price' => $this->pivot->firstClass_price,
            'secondClass_price' => $this->pivot->secondClass_price
        ];
    }
}
