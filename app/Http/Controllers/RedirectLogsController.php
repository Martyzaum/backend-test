<?php

namespace App\Http\Controllers;

use App\Repositories\RedirectLogsRepository;
use App\Repositories\RedirectRepository;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class RedirectLogsController extends Controller
{
    public function __construct(
        private RedirectLogsRepository $redirectLogsRepository,
        private RedirectRepository $redirectRepository
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
            $existCode = $this->redirectRepository->get($code);
            if (isset($existCode['status']) && $existCode['status'] === 'error') {
                return response()->json($existCode, 400);
            }

            $stats = $this->redirectLogsRepository->getLogStatsByCode($code);
            if ($stats['status'] === 'error') {
                return response()->json($stats, 400);
            }

            return response()->json($stats, 200);
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
