<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class QueryParamsService
{
    public function __construct()
    {
        //
    }

    /**
     * Check the query params
     * @param array $queryParams
     * @param string $destinyUrl
     * @return array
     */
    public function checkQueryParams($queryParams, $destinyUrl): array
    {
        try {
            $parsedUrl = parse_url($destinyUrl);
            $query = $parsedUrl['query'] ?? '';
            parse_str($query, $destinyUrlParams);

            $finalParams = [];

            foreach ($queryParams as $key => $value) {
                if (isset($destinyUrlParams[$key]) && !empty($value)) {
                    $finalParams[$key] = $value;
                } elseif (isset($destinyUrlParams[$key]) && !empty($destinyUrlParams[$key]) && empty($value)) {
                    $finalParams[$key] = $destinyUrlParams[$key];
                } elseif (!empty($value)) {
                    $finalParams[$key] = $value;
                }
            }

            $finalQuery = http_build_query($finalParams);

            if (!isset($parsedUrl['path'])) {
                $parsedUrl['path'] = '/';
            }

            $finalUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];

            if (!empty($finalQuery)) {
                $finalUrl .= '?' . $finalQuery;
            }

            Log::info('[QueryParamsService - checkQueryParams] Query params checked successfully!', ['data' => $finalUrl]);

            return [
                'status' => 'success',
                'message' => 'Query params checked successfully!',
                'data' => $finalUrl
            ];
        } catch (\Throwable $th) {
            Log::error('[QueryParamsService - checkQueryParams] An error occurred while trying to check the query params', ['error' => $th->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to check the query params',
                'error' => $th->getMessage()
            ];
        }
    }
}
