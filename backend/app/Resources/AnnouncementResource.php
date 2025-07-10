<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => (float) $this->getPrice()->getAmount(),
            'images' => collect($this->getImages())->map(fn($img) => asset('storage/' . $img->getPath())),
        ];
    }
}
