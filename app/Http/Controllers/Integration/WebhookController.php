<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Webhook\AddWebhookRequest;
use App\Http\Requests\Webhook\EditWebhookRequest;
use App\Http\Requests\Webhook\GetAllWebHookByShopRequest;
use App\Services\Webhook\WebhookService;
use Lcobucci\JWT\Configuration;

class WebhookController extends Controller
{
    public function index(
        GetAllWebHookByShopRequest $request,
        WebhookService $webhookService
    ) {
        $shopDomain = $request->query('shop_domain');
        $appId = $this->getClientId($request->bearerToken());
        return $webhookService->getWebhookByShopAndAppId($shopDomain, $appId);
    }

    public function destroy(
        $webhookId,
        WebhookService $webhookService
    ): \Illuminate\Http\JsonResponse {
        try {
            $result = $webhookService->deleteWebhook(
                $webhookId
            );
            if ($result) {
                return successfulResult("Delete webhook successfully");
            } else {
                return failedResult("Not found webhook");
            }
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());
            return failedResult($exception->getMessage());
        }
    }

    public function get(
        $webhookId,
        WebhookService $webhookService
    ) {
        return $webhookService->getWebhookById(
            $webhookId
        );
    }

    public function store(
        AddWebhookRequest $request,
        WebhookService $webhookService
    ): \Illuminate\Http\JsonResponse {
        $appId = $this->getClientId($request->bearerToken());
        $shopDomain = $request->get('shop_domain');
        $key = $request->get('key');
        $url = $request->get('url');

        try {
            $data = $webhookService->createWebhook(
                $shopDomain,
                $appId,
                $url,
                $key
            );
            return successfulResult("Create webhook successfully", $data);
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());
            return failedResult($exception->getMessage());
        }
    }

    public function update(
        $webhookId,
        EditWebhookRequest $request,
        WebhookService $webhookService
    ): \Illuminate\Http\JsonResponse {
        try {
            $url = $request->get('url');
            $key = $request->get('key');


            $newWebhook = $webhookService->updateWebhookById(
                $webhookId,
                $url,
                $key
            );
            if ($newWebhook) {
                return successfulResult("Update webhook successfully");
            }
            return failedResult("Update webhook failed");
        } catch (\Exception $exception) {
            logger()->error($exception);
            return failedResult($exception->getMessage());
        }
    }


    private function getClientId($token)
    {
        return Configuration::forUnsecuredSigner()->parser()->parse($token)
                   ->claims()->get('aud')[0];
    }
}
