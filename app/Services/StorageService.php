<?php

namespace App\Services;

use App\Models\Storage as StorageModel;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService
{
    /**
     * @param array        $data
     * @param UploadedFile $uploadedFile
     * @return StorageModel
     * @throws Exception
     */
    public function uploadFile(array $data, UploadedFile $uploadedFile)
    {
        $path = null;

        try {
            DB::beginTransaction();

            /** @var StorageModel $storage */
            $storage = StorageModel::query()
                ->create($data + [
                    'file_name'      => Str::random(32),
                    'file_extension' => pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION),
                    'file_mime_type' => $uploadedFile->getClientMimeType(),
                    'file_size'      => $uploadedFile->getSize(),
                ]);

            $fileName = $storage->file_name;
            $fileExtension = $storage->file_extension;

            $path = $uploadedFile->storePubliclyAs($storage->getPartitionDirectory(), $fileName . '.' . $fileExtension, 'public');

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            if ($path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }

        return $storage->fresh();
    }

    /**
     * @param StorageModel $storage
     * @throws Exception
     */
    public function deleteFile(StorageModel $storage)
    {
        try {
            DB::beginTransaction();

            $path = $storage->getPath();

            $storage->delete();

            Storage::disk('public')->delete($path);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
