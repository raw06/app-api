<?php

namespace App\Services\Integration;

use App\Models\PassportAuthCode;
use App\Models\PassportToken;
use App\Services\Base\BaseServiceImp;

class IntegrationServiceImp extends BaseServiceImp implements IntegrationService {

    public function removeTokenIntegration($userId, $shopDomain)
    {
        try {
            PassportToken::query()->where('user_id', $userId)->delete();
            PassportAuthCode::query()->where('user_id', $userId)->delete();
        } catch (\Exception $exception) {
            logger()->error("Error remove token . $shopDomain", [
                'msg' => $exception->getMessage()
            ]);
        }
    }
}
