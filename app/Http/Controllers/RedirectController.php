<?php

namespace App\Http\Controllers;

use App\Http\Requests\Redirects\StoreReq as RedirectsStoreReq;
use App\Http\Requests\Redirects\UpdateReq as RedirectsUpdateReq;
use App\Repositories\RedirectLogsRepository;
use App\Repositories\RedirectRepository;
use App\Services\QueryParamsService;
use App\Services\RedirectLogService;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    public function __construct(
        private RedirectRepository $redirectRepository,
        private RedirectLogsRepository $redirectLogsRepository,
        private RedirectLogService $redirectLogsService,
        private QueryParamsService $queryParamsService
    ) {
    }

    /**
     * Display a listing of the resource.
     * @param string $code
     * @return \Illuminate\Http\Response | string
     */
    public function index(string $code, Request $request)
    {

        try {
            $redirect = $this->redirectRepository->get($code);
            if (isset($redirect['status']) && $redirect['status'] === 'error') {
                return response()->json($redirect, 404);
            } else {
                Log::info('[RedirectController - index] Redirect found successfully!', ['data' => $redirect]);

                $queryParams = $request->query();

                $attLastAcess = $this->redirectRepository->update($code, ['last_access' => now('America/Sao_Paulo')->format('Y-m-d H:i:s')]);
                if ($attLastAcess['status'] === 'error') {
                    return response()->json($attLastAcess, 500);
                }

                $logData = $this->redirectLogsService->getLogData($redirect, $request, $queryParams);
                if ($logData['status'] === 'error') {
                    return response()->json($logData, 500);
                }

                $saveLog = $this->redirectLogsRepository->saveLog($logData['data']);
                if ($saveLog['status'] === 'error') {
                    return response()->json($saveLog, 500);
                }

                $finalUrl = $this->queryParamsService->checkQueryParams($queryParams, $redirect->destiny_url);
                if ($finalUrl['status'] === 'error') {
                    return response()->json($finalUrl, 500);
                }

                return redirect($finalUrl['data']);
            }
        } catch (\Throwable $th) {
            Log::error('[RedirectController - index] An error occurred while trying to get the redirect', ['error' => $th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while trying to get the redirect',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * @param string $code
     * @return \Illuminate\Http\Response
     */
    public function show($code = null)
    {
        try {
            if ($code) {
                $redirects = $this->redirectRepository->get($code);
            } else {
                $redirects = $this->redirectRepository->getAll();
            }

            if (isset($redirects['status']) && $redirects['status'] === 'error') {
                return response()->json($redirects, 404);
            } else {
                Log::info('[RedirectController - show] Redirects found successfully!', ['data' => $redirects]);
                return response()->json($redirects, 200);
            }
        } catch (\Throwable $th) {
            Log::error('[RedirectController - show] An error occurred while trying to get the redirects', ['error' => $th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while trying to get the redirects',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RedirectsStoreReq $request)
    {
        try {
            $save = $this->redirectRepository->save($request->destiny_url);
            if (isset($save['status']) && $save['status'] === 'error') {
                return response()->json($save, 500);
            } else {
                Log::info('[RedirectController - store] Redirect created successfully!', ['data' => $save]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Redirect created successfully!',
                    'data' => [
                        'code' => Hashids::encode($save->id),
                        'destiny_url' => $save->destiny_url,
                        'status' => 'created'
                    ]
                ], 201);
            }
        } catch (\Throwable $th) {
            Log::error('[RedirectController - store] An error occurred while trying to save the redirect', ['error' => $th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while trying to save the redirect',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param string $code
     * @param \App\Http\Requests\Redirects\UpdateReq $request
     * @return \Illuminate\Http\Response
     */
    public function update(RedirectsUpdateReq $request, $code)
    {
        try {
            $codeExists = $this->redirectRepository->get($code);
            if (isset($codeExists['status']) && $codeExists['status'] === 'error') {
                return response()->json($codeExists, 404);
            }

            $updateData = [
                'destiny_url' => $request->destiny_url ?? '',
                'status' => $request->status ?? ''
            ];

            if (empty($updateData['destiny_url']) && empty($updateData['status'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You must provide at least one field to update'
                ], 400);
            }

            $update = $this->redirectRepository->update($code, $updateData);
            if (isset($update['status']) && $update['status'] === 'error') {
                return response()->json($update, 500);
            } else {
                Log::info('[RedirectController - update] Redirect updated successfully!', ['data' => $update]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Redirect updated successfully!',
                    'data' => [
                        'code' => $code,
                        'destiny_url' => $update->destiny_url,
                        'status' => $update->status
                    ]
                ], 200);
            }
        } catch (\Throwable $th) {
            Log::error('[RedirectController - update] An error occurred while trying to update the redirect', ['error' => $th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while trying to update the redirect',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *  @param string $code
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        try {
            $codeExists = $this->redirectRepository->get($code);
            if (isset($codeExists['status']) && $codeExists['status'] === 'error') {
                return response()->json($codeExists, 404);
            }

            $desactivate = $this->redirectRepository->update($code, ['status' => 'inactive']);
            if (isset($desactivate['status']) && $desactivate['status'] === 'error') {
                return response()->json($desactivate, 500);
            }

            $delete = $this->redirectRepository->delete($code);
            if (isset($delete['status']) && $delete['status'] === 'error') {
                return response()->json($delete, 500);
            } else {
                Log::info('[RedirectController - destroy] Redirect deleted successfully!', ['data' => $delete]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Redirect deleted successfully!',
                    'data' => [
                        'code' => $code,
                        'status' => 'deleted'
                    ]
                ], 200);
            }
        } catch (\Throwable $th) {
            Log::error('[RedirectController - destroy] An error occurred while trying to delete the redirect', ['error' => $th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while trying to delete the redirect',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
