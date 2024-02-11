<?php

namespace App\Http\Controllers;

use App\Http\Requests\Redirects\StoreReq as RedirectsStoreReq;
use App\Http\Requests\Redirects\UpdateReq as RedirectsUpdateReq;
use App\Repositories\RedirectRepository;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    public function __construct(private RedirectRepository $redirectRepository)
    {
    }

    /**
     * Display a listing of the resource.
     * @param string $code
     * @return \Illuminate\Http\Response | string
     */
    public function index(string $code)
    {
        try {
            $redirect = $this->redirectRepository->getRedirect($code);
            if (isset($redirect['status']) && $redirect['status'] === 'error') {
                return response()->json($redirect, 404);
            } else {
                Log::info('[RedirectController - index] Redirect found successfully!', ['data' => $redirect]);
                return redirect($redirect->destiny_url);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RedirectsStoreReq $request)
    {
        try {
            $save = $this->redirectRepository->saveRedirect($request->destiny_url);
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
            $codeExists = $this->redirectRepository->getRedirect($code);
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

            $update = $this->redirectRepository->updateRedirect($code, $updateData);
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
            $codeExists = $this->redirectRepository->getRedirect($code);
            if (isset($codeExists['status']) && $codeExists['status'] === 'error') {
                return response()->json($codeExists, 404);
            }

            $delete = $this->redirectRepository->deleteRedirect($code);
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
