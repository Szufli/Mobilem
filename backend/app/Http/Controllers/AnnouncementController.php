<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Application\Announcement\UseCase\CreateAnnouncement;
use App\Application\Announcement\UseCase\UpdateAnnouncement;
use App\Application\Announcement\UseCase\ListAnnouncements;
use App\Application\Announcement\UseCase\DeleteAnnouncement;

use App\Application\Announcement\Validation\CreateAnnouncementValidator;
use App\Application\Announcement\Validation\UpdateAnnouncementValidator;

use App\Application\Announcement\DTO\CreateAnnouncementDTO;
use App\Application\Announcement\DTO\UpdateAnnouncementDTO;

use App\Resources\AnnouncementResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnnouncementController extends Controller
{
    public function index(ListAnnouncements $useCase): AnonymousResourceCollection
    {
        $announcements = $useCase->handle();

        return AnnouncementResource::collection($announcements);
    }

    public function store(
        Request $request,
        CreateAnnouncement $createAnnouncement,
        CreateAnnouncementValidator $validator
    ) : AnnouncementResource {
        $dto = CreateAnnouncementDTO::fromRequest($request);
        $validator->validate($dto);

        $announcement = $createAnnouncement->handle($dto);

        return new AnnouncementResource($announcement);
    }

    public function update(
        int $id,
        Request $request,
        UpdateAnnouncement $useCase,
        UpdateAnnouncementValidator $validator
    ): AnnouncementResource {
        $dto = UpdateAnnouncementDTO::fromRequest($request, $id);

        $validator->validate($dto);

        $announcement = $useCase->handle($dto);

        return new AnnouncementResource($announcement);
    }


    public function destroy(int $id, DeleteAnnouncement $useCase) : \Illuminate\Http\JsonResponse
    {
        $useCase->handle($id);
        return response()->json(['message' => "Ogłoszenie o ID $id zostało usunięte."]);
    }
}
