<?php
namespace App\Domain\Announcement\Entities;

class Image
{
    private ?int $id = null;
    private ?int $announcementId = null;
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getAnnouncementId(): ?int
    {
        return $this->announcementId;
    }

    public function setAnnouncementId(int $announcementId): void
    {
        $this->announcementId = $announcementId;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
