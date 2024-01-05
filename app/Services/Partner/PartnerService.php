<?php

namespace App\Services\Partner;

use App\Services\Base\BaseServiceInterface;

interface PartnerService extends BaseServiceInterface {
    public function all(): array;
}
