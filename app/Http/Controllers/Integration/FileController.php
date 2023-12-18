<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Services\File\FileService;
use App\Traits\InstalledShop;
use Illuminate\Http\Request;

class FileController extends Controller
{
    use InstalledShop;

    public $fileService;

    public function __construct(FileService  $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index() {
        $shopId = $this->shopId();

        if(!$shopId) {
            return response()->json([
                'message' => 'Failed'
            ], 500);
        }
        return $this->fileService->getFileByShopId($shopId);
    }

    public function store(Request $request) {
        $shopId = $this->shopId();
        if(!$shopId) {
            return response()->json([
                'message' => 'Failed'
            ], 500);
        }
        $file = $request->file()['file'];
        $createFile = $this->fileService->createFile($shopId, $file);

        if(!$createFile) {
            return response()->json([
                'message' => 'Create file failed'
            ], 500);
        }

        return response()->json([
            'message' => 'Create file successfully'
        ]);
    }

    public function destroy($id) {
        $deleteFile = $this->fileService->removeFile($id);

        if(!$deleteFile) {
            return response()->json([
                'message' => 'Delete file failed'
            ], 500);
        }

        return response()->json([
            'message' => 'Delete file successfully'
        ]);
    }
}
