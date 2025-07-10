<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Announcement\Entities\Announcement as DomainAnnouncement;
use App\Domain\Announcement\Entities\Image as DomainImage;
use App\Domain\Announcement\Contracts\AnnouncementRepositoryInterface;
use App\Domain\Announcement\ValueObject\Price;
use App\Models\Announcement;
use App\Models\AnnouncementImage;
use Illuminate\Support\Facades\DB;

class EloquentAnnouncementRepository implements AnnouncementRepositoryInterface
{
    public function save(DomainAnnouncement $domain): DomainAnnouncement
    {
        return DB::transaction(function () use ($domain) {
            $model = Announcement::create([
                'name' => $domain->getName(),
                'description' => $domain->getDescription(),
                'price' => $domain->getPrice()->getAmount()
            ]);

            foreach ($domain->getImages() as $img) {
                AnnouncementImage::create([
                    'announcement_id' => $model->id,
                    'path' => $img->getPath(),
                ]);
            }

            $model->refresh();

            return $this->mapToDomain($model);
        });
    }

    public function all(): array
    {
        return Announcement::with('images')
            ->get()
            ->map(fn($model) => $this->mapToDomain($model))
            ->all();
    }

    public function findById(int $id): DomainAnnouncement
    {
        $model = Announcement::with('images')->findOrFail($id);

        return $this->mapToDomain($model);
    }

    public function update(DomainAnnouncement $domain): DomainAnnouncement
    {
        return DB::transaction(function () use ($domain) {
            $model = Announcement::findOrFail($domain->getId());

            $model->update([
                'name' => $domain->getName(),
                'description' => $domain->getDescription(),
                'price' => $domain->getPrice()->getAmount()
            ]);

            AnnouncementImage::where('announcement_id', $model->id)->delete();

            foreach ($domain->getImages() as $img) {
                AnnouncementImage::create([
                    'announcement_id' => $model->id,
                    'path' => $img->getPath()
                ]);
            }

            $model->refresh();

            return $this->mapToDomain($model);
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $model = Announcement::findOrFail($id);
            $model->images()->delete();
            $model->delete();
        });
    }

    private function mapToDomain(Announcement $model): DomainAnnouncement
    {
        $announcement = new DomainAnnouncement(
            $model->name,
            $model->description,
            new Price($model->price)
        );
        $announcement->setId($model->id);

        foreach ($model->images as $img) {
            $image = new DomainImage($img->path);
            $image->setId($img->id);
            $image->setAnnouncementId($img->announcement_id);
            $announcement->addImage($image);
        }

        return $announcement;
    }
    
}
