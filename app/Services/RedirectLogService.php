<?php

namespace App\Services;

use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Log;

class RedirectLogService
{
    public function __construct()
    {
        //
    }

    /**
     * Get log data
     * @param object $redirect
     * @param Request $request
     * @param array $queryParams
     * @return array
     */
    public function getLogData($redirect, $request, $queryParams): array
    {
        try {
            $code = Hashids::decode($redirect->code);

            $logData = [
                'redirect_id' => $code[0],
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'referer' => $request->header('Referer'),
                'query_params' => json_encode($queryParams),
                'accessed_at' => now('America/Sao_Paulo')->format('Y-m-d H:i:s')
            ];

            Log::info('[RedirectLogService - getLogData] Log data retrieved successfully!', ['data' => $logData]);
            return [
                'status' => 'success',
                'message' => 'Log data retrieved successfully!',
                'data' => $logData
            ];
        } catch (\Throwable $th) {
            Log::error('[RedirectLogService - getLogData] An error occurred while trying to get the log data', ['error' => $th->getMessage()]);

            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to get the log data',
                'error' => $th->getMessage()
            ];
        }
    }
}
