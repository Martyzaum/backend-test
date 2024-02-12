<?php

namespace App\Repositories;

use App\Models\RedirectLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class RedirectLogsRepository
{
    public function __construct(private RedirectLogs $redirectLogModel)
    {
    }

    /**
     * Save a log
     * @param array $logData
     * @return array
     */
    public function saveLog(array $logData): array
    {
        try {
            $log = $this->redirectLogModel->insert($logData);
            Log::info('[RedirectLogsRepository - saveLog] Log saved successfully!', ['data' => $log]);
            return [
                'status' => 'success',
                'message' => 'Log saved successfully!',
                'data' => $log
            ];
        } catch (\Throwable $th) {
            dump($th, $logData);
            Log::error('[RedirectLogsRepository - saveLog] An error occurred while trying to save the log', ['error' => $th->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to save the log',
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Get stats from a redirect
     * @param string $code
     * @return array
     */
    public function getLogStatsByCode(string $code): array
    {
        try {
            $id = Hashids::decode($code);

            $logs = $this->redirectLogModel
                ->where('redirect_id', $id)
                ->selectRaw('
                ip,
                referer,
                date(accessed_at) as date
            ')
                ->get();

            $totalAccesses = $logs->count();
            $uniqueAccesses = $logs->groupBy('ip')->count();
            $topReferrers = $logs->groupBy('referer')->sortByDesc(function ($group) {
                return $group->count();
            })->take(5)->keys()->toArray();

            $accessesLast10Days = $logs->filter(function ($log) {
                return Carbon::parse($log->date)->gte(Carbon::now()->subDays(10));
            })->groupBy('date')->map(function ($dateGroup) {
                return [
                    'total' => $dateGroup->count(),
                    'unique' => $dateGroup->groupBy('ip')->count()
                ];
            })->toArray();

            Log::info('[RedirectLogsRepository - getLogStatsByCode] Stats retrieved successfully!', [
                'total_accesses' => $totalAccesses,
                'unique_accesses' => $uniqueAccesses,
                'top_referrers' => $topReferrers,
                'accesses_last_10_days' => $accessesLast10Days,
            ]);

            return [
                'total_accesses' => $totalAccesses,
                'unique_accesses' => $uniqueAccesses,
                'top_referrers' => $topReferrers,
                'accesses_last_10_days' => $accessesLast10Days,
            ];
        } catch (\Throwable $th) {
            Log::error('[RedirectLogsRepository - getLogStatsByCode] An error occurred while trying to retrieve the stats', ['error' => $th->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to retrieve the stats',
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Get logs by code
     * @param string $code
     * @return array
     */
    public function getLogsByCode(string $code): array
    {
        try {
            $id = Hashids::decode($code);

            $logs = $this->redirectLogModel
                ->where('redirect_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('[RedirectLogsRepository - getLogsByCode] Logs retrieved successfully!', ['data' => $logs]);

            return [
                'status' => 'success',
                'message' => 'Logs retrieved successfully!',
                'data' => $logs
            ];
        } catch (\Throwable $th) {
            Log::error('[RedirectLogsRepository - getLogsByCode] An error occurred while trying to retrieve the logs', ['error' => $th->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to retrieve the logs',
                'error' => $th->getMessage()
            ];
        }
    }


}
