<?php

namespace App\Application\Announcement\UseCase;

use App\Domain\Announcement\Contracts\AnnouncementRepositoryInterface;
use App\Domain\Announcement\Entities\Announcement;

class ListAnnouncements
{
    public function __construct(
        private AnnouncementRepositoryInterface $repository
    ) {}

    /**
     * @return Announcement[]
     */
    public function handle(): array
    {
        return $this->repository->all();
    }
}
