<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Announcement\Contracts\AnnouncementRepositoryInterface;
use App\Domain\Announcement\Contracts\UploaderInterface;
use App\Infrastructure\Persistence\Eloquent\EloquentAnnouncementRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUploader;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {

        $this->app->bind(AnnouncementRepositoryInterface::class, EloquentAnnouncementRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
