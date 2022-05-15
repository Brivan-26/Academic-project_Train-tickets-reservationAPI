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
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'assigned_to' => new UserResource($this->assignedTo),
            'is_active' => $this->is_active,
            'description' => $this->description,
            'title' => $this->title
        ];
    }
}
