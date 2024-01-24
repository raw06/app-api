<?php

namespace App\Services\File;

use App\Models\Shop;
use App\Repositories\File\FileRepository;
use App\Services\Base\BaseServiceImp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileServiceImp extends BaseServiceImp implements FileService {
    protected $_repository;

    public function __construct(FileRepository $_repository)
    {
        $this->_repository = $_repository;
    }

    private function storeFile(UploadedFile $file, string $shopName, string $type = 'files') {
        $extension = $file->extension();
        $fileName = now()->timestamp.rand(0,100);
        Storage::putFileAs("public/$shopName/$type", $file, "$fileName.$extension");
        return config('app.url'). Storage::disk('local')->url("public/$shopName/$type/$fileName.$extension");
    }

    private function deleteFile($fileUrl): bool
    {
        return Storage::delete($fileUrl);
    }

    public function createFile($shopId, UploadedFile $file)
    {
        $shop = Shop::query()->find($shopId);
        if(!$shop) {
            return false;
        }
        try {
            $storeFile = $this->storeFile($file, $shop->shop);
            return $this->_repository->create([
                'shop_id' => $shopId,
                'name' => collect(explode(".", $file->getClientOriginalName()))->first(),
                'url' => $storeFile,
                'type' => $file->extension()
            ]);
        } catch (\Exception $exception) {
            logger()->error("error store file", [
                'err' => $exception
            ]);
            return false;
        }
    }

    public function removeFile($fileId)
    {
        $fileUrl = $this->_repository->select()->where('id', $fileId)->first();

        if(!$fileUrl) {
            return false;
        }
        try {
            $this->deleteFile($fileUrl->url);
            return $fileUrl->delete();
        } catch (\Exception $exception) {
            logger()->error("Error remove file", [
                'err' => $exception
            ]);
            return false;
        }
    }

    public function getFileByShopId($shopId)
    {
        return $this->_repository->select()->where('shop_id', $shopId)->get()->toArray();
    }


}
