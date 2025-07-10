<?php

namespace App\Application\Announcement\UseCase;
use InvalidArgumentException;

use App\Application\Announcement\DTO\CreateAnnouncementDTO;
use App\Domain\Announcement\Entities\Announcement;
use App\Domain\Announcement\Entities\Image;
use App\Domain\Announcement\ValueObject\Price;
use App\Domain\Announcement\Contracts\AnnouncementRepositoryInterface;

class CreateAnnouncement
{
    public function __construct(
        private AnnouncementRepositoryInterface $repository
    ) {}

    public function handle(CreateAnnouncementDTO $dto): Announcement
    {
        try {
            $price = new Price($dto->price);
        } catch (InvalidArgumentException $e) {
            throw new \RuntimeException("Niepoprawna cena: " . $e->getMessage());
        }

        $announcement = new Announcement(
            $dto->name,
            $dto->description,
            $price
        );

        foreach ($dto->newImages as $uploadedFile) {
            $path = $uploadedFile->store('announcements', 'public'); // zapis w storage/app/public/announcements
            $announcement->addImage(new Image($path));
        }

        $this->repository->save($announcement);

        return $announcement;
    }
}
