<?php

namespace App\Application\Announcement\UseCase;

use App\Domain\Announcement\Contracts\AnnouncementRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteAnnouncement
{
    public function __construct(
        private AnnouncementRepositoryInterface $repository
    ) {}

    public function handle(int $id): void
    {
        $announcement = $this->repository->findById($id);

        if (!$announcement) {
            throw new NotFoundHttpException("Ogłoszenie o ID $id nie zostało znalezione.");
        }


        foreach ($announcement->getImages() as $image) {
            Storage::disk('public')->delete($image->getPath());
        }
        
        $this->repository->delete($id);
    }
}
