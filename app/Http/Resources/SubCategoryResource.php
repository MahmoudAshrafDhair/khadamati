<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
            'worker_count' => $this->workers->count(),
            'image' => $this->image == null ? asset('assets/image/defoult.png') : asset('storage/'.$this->image),
            'category' => $this->category->name,
        ];
    }
}
