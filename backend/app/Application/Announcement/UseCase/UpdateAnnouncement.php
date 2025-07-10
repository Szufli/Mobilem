<?php
// app/Application/Announcement/UseCase/UpdateAnnouncement.php

namespace App\Application\Announcement\UseCase;
use InvalidArgumentException;
use App\Application\Announcement\DTO\UpdateAnnouncementDTO;
use App\Domain\Announcement\Entities\Image;
use App\Domain\Announcement\ValueObject\Price;
use App\Domain\Announcement\Contracts\AnnouncementRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class UpdateAnnouncement
{
    public function __construct(
        private AnnouncementRepositoryInterface $repository
    ) {}

    private function normalizePath(string $path): string
    {
        return preg_replace('#^storage/#', '', $path);
    }

    public function handle(UpdateAnnouncementDTO $dto)
    {
        $announcement = $this->repository->findById($dto->id);
        try {
            $price = new Price($dto->price);
        } catch (InvalidArgumentException $e) {
            throw new \RuntimeException("Niepoprawna cena: " . $e->getMessage());
        }
        $announcement->setName($dto->name);
        $announcement->setDescription($dto->description);
        $announcement->setPrice($price);

        $normalizedKeep = array_map(fn($p) => $this->normalizePath($p), $dto->existingImages);

        foreach ($announcement->getImages() as $img) {
            $imgPathNormalized = $this->normalizePath($img->getPath());

            if (!in_array($imgPathNormalized, $normalizedKeep, true)) {
                Storage::disk('public')->delete($img->getPath());
                $announcement->removeImageByPath($img->getPath());
            }
        }


        foreach ($dto->newImages as $file) {
            $path = $file->store('announcements', 'public');
            $announcement->addImage(new Image($path));
        }

        $this->repository->update($announcement);

        return $announcement;
    }
}
