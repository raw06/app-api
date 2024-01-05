<?php

namespace App\Http\Controllers;

use App\Services\Partner\PartnerService;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index() {
        /** @var PartnerService $partnerService */
        $partnerService = app(PartnerService::class);

        $response = $partnerService->all();
        return response()->json($response);
    }
}
