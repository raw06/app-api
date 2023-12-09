<?php

namespace App\Repositories\File;

use App\Models\File;
use App\Repositories\Base\RepositoryEloquent;

class FileRepositoryEloquent extends RepositoryEloquent implements FileRepository {

    public function getModel()
    {
        return File::class;
    }
}
