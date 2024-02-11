<?php

namespace App\Http\Controllers;

use App\Http\Requests\Redirects\StoreReq as RedirectsStoreReq;
use App\Repositories\RedirectRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    public function __construct(private RedirectRepository $redirectRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
                    'data' => $save
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
