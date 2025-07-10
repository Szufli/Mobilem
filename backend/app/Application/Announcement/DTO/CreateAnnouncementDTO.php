<?php

namespace App\Application\Announcement\DTO;

use Illuminate\Http\Request;

class CreateAnnouncementDTO
{
    public function __construct(
        public string $name,
        public string $description,
        public float $price,
        public array $newImages = []
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('name', ''),
            $request->input('description', ''),
            (float) $request->input('price', 0),
            $request->file('newImages') ?? []
        );
    }
}
