<?php

namespace App\Services\File;

use App\Services\Base\BaseServiceInterface;
use Illuminate\Http\UploadedFile;

interface FileService extends BaseServiceInterface  {

    public function getFileByShopId($shopId);

    public function createFile(int $shopId, UploadedFile $file);

    public function removeFile(int $fileId);
}
