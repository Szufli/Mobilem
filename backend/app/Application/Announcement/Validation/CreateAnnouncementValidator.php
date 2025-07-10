<?php

namespace App\Application\Announcement\Validation;

use App\Application\Announcement\DTO\CreateAnnouncementDTO;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\ValidationException;

class CreateAnnouncementValidator
{
    public function __construct(private ValidatorFactory $validatorFactory) {}

    public function validate(CreateAnnouncementDTO $dto): void
    {
        $validator = $this->validatorFactory->make([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'newImages' => $dto->newImages,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric'],
            'newImages' => ['nullable', 'array', 'max:5'],
            'newImages.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB per file
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
