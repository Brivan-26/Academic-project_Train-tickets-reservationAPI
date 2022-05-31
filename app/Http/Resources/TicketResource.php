<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\StationResource as StationResource;
class TicketResource extends JsonResource
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
            'payer' =>  $this->user->first_name. ' '. $this->user->last_name,
            'travel_id' => $this->travel_id,
            'passenger_name' => $this->passenger_name,
            'travel_class' => $this->travel_class,
            'payment_method' => $this->payment_method,
            //'payment_token' => $this->payment_token,
            'qrcode_token' => $this->qrcode_token,
            'validated' => $this->validated == 1,
            'boarding_station' => new StationResource($this->boardStation),
            'landing_station' => new StationResource($this->landStation),
            'price' => $this->price,
            'created_at' => $this->created_at ? ($this->created_at->diffForHumans()) : null,
            //'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans(): $this->updated_at
        ];
    }
}
