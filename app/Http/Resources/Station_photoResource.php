<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Station_photoResource extends JsonResource
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
            'station_id' => $this->station_id,
            'photo_url' => $this->photo_url,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : $this->updated_at
        ];
    }
}
