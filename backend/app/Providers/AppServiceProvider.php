<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Announcement\Contracts\AnnouncementRepositoryInterface;
use App\Domain\Announcement\Contracts\UploaderInterface;
use App\Infrastructure\Persistence\Eloquent\EloquentAnnouncementRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUploader;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Rejestracja usług kontenera.
     */
    public function register(): void
    {
        // Bindowanie interfejsów do implementacji
        $this->app->bind(AnnouncementRepositoryInterface::class, EloquentAnnouncementRepository::class);
    }

    /**
     * Bootstrapowanie dowolnych usług aplikacji.
     */
    public function boot(): void
    {
        //
    }
}
