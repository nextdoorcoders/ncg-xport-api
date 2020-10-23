<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storage as StorageRequest;
use App\Http\Resources\DataResource;
use App\Models\Storage as StorageModel;
use App\Services\StorageService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class StorageController extends Controller
{
    /**
     * @var StorageService
     */
    private StorageService $storageService;

    /**
     * StorageController constructor.
     *
     * @param StorageService $storageService
     */
    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * @param StorageRequest $request
     * @return DataResource
     * @throws Exception
     */
    public function uploadFile(StorageRequest $request)
    {
        $data = json_decode($request->get('data'), true);
        $file = $request->file('file');

        $response = $this->storageService->uploadFile($data, $file);

        return new DataResource($response);
    }

    /**
     * @param StorageModel $storage
     * @return Response
     * @throws Exception
     */
    public function deleteFiles(StorageModel $storage)
    {
        $this->storageService->deleteFile($storage);

        return response()->noContent();
    }
}
