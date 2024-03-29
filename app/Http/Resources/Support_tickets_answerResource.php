<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Support_tickets_answerResource extends JsonResource
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
        return[
            'support_ticket' => new Support_ticketResource($this->support_ticket),
            'from' => new UserResource($this->sender),
            'to' => new UserResource($this->receiver),
            'content' => $this->content
        ];
    }
}
