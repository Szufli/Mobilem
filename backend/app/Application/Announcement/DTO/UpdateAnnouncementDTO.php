<?php

namespace App\Application\Announcement\DTO;

use Illuminate\Http\Request;

class UpdateAnnouncementDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public float $price,
        public array $existingImages = [],
        public array $newImages = [],
    ) {}

    public static function fromRequest(Request $request, int $id): self
    {
        return new self(
            $id,
            $request->input('name', ''),
            $request->input('description', ''),
            (float) $request->input('price', 0),
            $request->input('existingImages', []),
            $request->file('newImages') ?? [] 
        );
    }
}
