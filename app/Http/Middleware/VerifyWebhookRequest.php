<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyWebhookRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!isValidWebhookRequest($request)) {
            $header = $request->header();
            Log::error("invalid webhook bad request header " . json_encode($header));

            return redirect('message')->with(['message' => 'invalid webhook bad request!']);
        }

        return $next($request);
    }
}
