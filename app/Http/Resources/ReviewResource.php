<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'user'=> new UserResource($this->user),
            'travel' => new TravelResource($this->travel),
            'passenger_name' => $this->passenger_name,
            'content' => $this->content,
            'rating' => $this->rating,
        ];
    }
}
