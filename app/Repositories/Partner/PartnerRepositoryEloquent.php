<?php

namespace App\Repositories\Partner;

use App\Models\Partner;
use App\Repositories\Base\RepositoryEloquent;

class PartnerRepositoryEloquent extends RepositoryEloquent implements PartnerRepository {

    public function getModel()
    {
       return Partner::class;
    }
}
