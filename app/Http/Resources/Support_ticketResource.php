<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Support_ticketResource extends JsonResource
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
        $user = $this->user();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'assigned_to' => $this->assigned_to,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'title' => $this->title
        ];
    }
}
