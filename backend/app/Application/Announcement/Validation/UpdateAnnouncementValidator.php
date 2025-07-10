<?php
// app/Application/Announcement/Validation/UpdateAnnouncementValidator.php

namespace App\Application\Announcement\Validation;

use App\Application\Announcement\DTO\UpdateAnnouncementDTO;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\ValidationException;

class UpdateAnnouncementValidator
{
    public function __construct(private ValidatorFactory $validatorFactory) {}

    public function validate(UpdateAnnouncementDTO $dto): void
    {
        $data = [
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'existingImages' => $dto->existingImages,
            'newImages' => $dto->newImages,
        ];
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric'],
            'existingImages' => ['nullable', 'array'],
            'existingImages.*' => ['string'],
            'newImages' => ['nullable', 'array', 'max:5'],
            'newImages.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        $validator = $this->validatorFactory->make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
