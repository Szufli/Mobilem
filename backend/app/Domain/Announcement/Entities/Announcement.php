<?php

namespace App\Domain\Announcement\Entities;

use App\Domain\Announcement\ValueObject\Price;

class Announcement
{

    public function __construct(
        private string $name,
        private string $description,
        private Price $price,
        private array $newImages = [],
        private ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    public function getImages(): array
    {
        return $this->newImages;
    }

    public function addImage(Image $image): void
    {
        $this->newImages[] = $image;
    }

    public function removeImageByPath(string $path): void
    {
        $this->newImages = array_values(array_filter($this->newImages, fn($img) => $img->getPath() !== $path));
    }
}
