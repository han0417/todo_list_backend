<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Log;
use Closure;

/**
 * 記錄請求/回應。
 * 記錄API的進出內容，可以當作分析依據，或是保留下來有議題時可以當作依據。
 *
 * @category   App
 * @package    Http
 * @subpackage Middleware
 */
class LogRequestResponse
{

    /**
     * 處理請求。
     *
     * @param mixed   $request HTTP請求。
     * @param Closure $next    下一步。
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * 接收請求及回應。
     *
     * @param mixed $request  HTTP請求。
     * @param mixed $response 回應。
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function terminate($request, $response): void
    {
        // 過濾部分API，不紀錄
        $isLog = $this->prepareToLog($request, $response);
        if ($isLog) {
            try {
                // 成功api log
                $this->logSuccess($request, $response);
            } catch (\Throwable $th) {
                // 異常api log
                $this->logException($request, $response, $th);
            }
        }
    }

    /**
     * @param $request
     * @param $response
     *
     * @return bool
     */
    private function prepareToLog($request, $response)
    {
        // 過濾關鍵字
        $pathKeywords = [
            'health_check',
        ];

        // 過濾部分紀錄
        foreach ($pathKeywords as $keyword) {
            if (strpos($request->path(), $keyword) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $request
     * @param $response
     */
    private function logSuccess($request, $response)
    {
        Log::info(
            "[Api] - " . $request->method() . " /" . $request->path(),
            [
                'request_id' => $request->requestId,
                "url"        => $request->fullUrl(),
                'ip'         => $request->ip(),
                'input'      => [
                    "time"   => $request->requestTime,
                    "header" => $request->headers->all(),
                    "data"   => $request->all()
                ],
                'output'     => [
                    "time" => Carbon::now()->toDateTimeString(),
                    "data" => json_decode($response->getContent(), true)
                ],
            ]
        );
    }

    /**
     * @param $request
     * @param $response
     * @param $th
     */
    private function logException($request, $response, $th)
    {
        Log::warning(
            "[Log Fail] - " . $request->method() . " /" . $request->path(),
            [
                'request_id' => $request->requestId,
                "url"        => $request->fullUrl(),
                'ip'         => $request->ip(),
                'input'      => [
                    "time"   => $request->requestTime,
                    "header" => $request->headers->all(),
                    "data"   => $request->all()
                ],
                'output'     => [
                    "time" => Carbon::now()->toDateTimeString(),
                    "data" => $th->getMessage()
                ],
            ]
        );
    }
}
