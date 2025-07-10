<?php

namespace App\Domain\Announcement\Contracts;

use App\Domain\Announcement\Entities\Announcement;

interface AnnouncementRepositoryInterface
{
    public function save(Announcement $announcement): Announcement;

    public function findById(int $id): Announcement;

    public function update(Announcement $announcement): Announcement;

    public function all(): array;

    public function delete(int $id): void;
}
