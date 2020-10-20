<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\MediaSync as MediaSyncRequest;
use App\Http\Resources\Vendor\MediaSync as MediaSyncResponse;
use App\Services\Vendor\MediaSyncService;
use Illuminate\Http\Request;

class MediaSyncController extends Controller
{
    protected MediaSyncService $mediaSyncService;

    public function __construct(MediaSyncService $mediaSyncService)
    {
        $this->mediaSyncService = $mediaSyncService;
    }

    /**
     * @param MediaSyncRequest $request
     * @return MediaSyncResponse
     */
    public function createMediaSync(MediaSyncRequest $request)
    {
        $data = $request->validated();

        $response = $this->mediaSyncService->createMediaSync($data);

        return new MediaSyncResponse($response);
    }
}
