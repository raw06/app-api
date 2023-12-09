<?php

namespace App\Services\Integration;

use App\Services\Base\BaseServiceInterface;

interface IntegrationService extends BaseServiceInterface {
    public function removeTokenIntegration($userId, $shopDomain);
}
