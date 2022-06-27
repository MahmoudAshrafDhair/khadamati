<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PagesResource extends JsonResource
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
            'terms_and_conditions' => $this->Terms_and_Conditions,
            'privacy_policy' => $this->privacy_policy,
        ];
    }
}
