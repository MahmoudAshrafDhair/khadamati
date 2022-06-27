<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerProfileResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'active' => $this->active,
            'city' => $this->city->name,
            'rating' => $this->rate_average,
            'appointments' => AppointmentResource::collection($this->appointments),
            'image' => $this->image == null ? asset('aassets/image/guest-user.jpg') : asset('storage/'.$this->image),
        ];
    }
}
