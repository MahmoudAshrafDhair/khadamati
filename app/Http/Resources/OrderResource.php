<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $resoure = [
            'id' => $this->id,
            'subCategory' => new SubCategoryResource($this->subCategories),
            'user' => new UserResource($this->user),
            'worker' => new WorkerResource($this->worker),
            'time_type' => $this->time_type == 1 ? __('api.now'): __('api.later'),
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'image' => $this->image == null ? asset('assets/image/defoult.png') : asset('storage/'.$this->image),
            'description' => $this->description,
            'is_completed' => $this->is_completed,
            'type' => $this->type,
        ];

        if ($this->time_type == 2) {
            $resoure['date'] = $this->date;
        }

        if ($this->type == 4) {
            $resoure['rates'] = RateResource::collection($this->rates);
        }

        return $resoure;

    }
}
