<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

/**
 * Request轉換
 */
class ModIncomingRequest
{

    /**
     * Handle an incoming request.
     *
     * @param |\Illuminate\Http\Request $request REQUEST
     * @param \Closure                  $next    NEXT
     *
     * @return mixed REQUEST
     */
    public function handle($request, Closure $next)
    {
        // 增加RequestId
        if (!empty($request->header("Request-Id"))) {
            $requestId = $request->header("Request-Id");
        } else {
            $requestId = app()->make('RequestId');
        }

        $request->requestId   = $requestId;
        $request->requestTime = Carbon::now()->toDateTimeString();

        return $next($request);
    }
}
