<?php

namespace App\Http\Controllers;

use App\Repositories\RedirectLogsRepository;
use Illuminate\Http\Request;

class RedirectLogsController extends Controller
{
    public function __construct(
        private RedirectLogsRepository $redirectLogsRepository
    ) {
    }

    /**
     * Get stats from a redirect
     * @param string $code
     * @return \Illuminate\Http\Response
     */
    public function stats(string $code)
    {
        try {
            $stats = $this->redirectLogsRepository->getLogStatsByCode($code);

            return response()->json([
                'status' => 'success',
                'message' => 'Stats retrieved successfully!',
                'data' => $stats
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while trying to get the stats',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get logs from a redirect
     * @param string $code
     * @return \Illuminate\Http\Response
     */
    public function logs(string $code, Request $request)
    {
        try {
            $logs = $this->redirectLogsRepository->getLogsByCode($code);

            return response()->json([
                'status' => 'success',
                'message' => 'Logs retrieved successfully!',
                'data' => $logs
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while trying to get the logs',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
