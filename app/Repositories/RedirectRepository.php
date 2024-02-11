<?php

namespace App\Repositories;

use App\Models\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class RedirectRepository
{
    protected $redirectModel;

    public function __construct(Redirect $redirectModel)
    {
        $this->redirectModel = $redirectModel;
    }

    public function saveRedirect(string $destinyUrl): array | Redirect
    {
        try {
            DB::beginTransaction();

            $redirect = Redirect::create(['destiny_url' => $destinyUrl]);
            $redirect->code = Hashids::encode($redirect->id);
            $redirect->save();

            DB::commit();

            Log::info('[RedirectRepository - saveRedirect] Redirect saved successfully!', ['data' => $redirect]);

            return $redirect;
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('[RedirectRepository - saveRedirect] An error occurred while trying to save the redirect', ['error' => $th->getMessage()]);

            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to save the redirect',
                'error' => $th->getMessage()
            ];
        }
    }

    public function getRedirect(string $code): array | Redirect
    {
        try {
            $id = Hashids::decode($code);
            if (empty($id)) {
                Log::error('[RedirectRepository - getRedirect] Redirect not found!', ['code' => $code]);

                return [
                    'status' => 'error',
                    'message' => 'Redirect not found!'
                ];
            }

            $redirect = Redirect::find($id[0]);
            if (empty($redirect)) {
                Log::error('[RedirectRepository - getRedirect] Redirect not found!', ['code' => $code]);

                return [
                    'status' => 'error',
                    'message' => 'Redirect not found!'
                ];
            }

            Log::info('[RedirectRepository - getRedirect] Redirect found successfully!', ['data' => $redirect]);

            return $redirect;
        } catch (\Throwable $th) {
            Log::error('[RedirectRepository - getRedirect] An error occurred while trying to get the redirect', ['error' => $th->getMessage()]);

            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to get the redirect',
                'error' => $th->getMessage()
            ];
        }
    }

    public function updateRedirect(string $code, array $data): array | Redirect
    {
        try {
            $id = Hashids::decode($code);

            $redirect = Redirect::find($id[0]);
            if (empty($redirect)) {
                Log::error('[RedirectRepository - updateRedirect] Redirect not found!', ['code' => $code]);

                return [
                    'status' => 'error',
                    'message' => 'Redirect not found!'
                ];
            }

            $redirect->update($data);

            Log::info('[RedirectRepository - updateRedirect] Redirect updated successfully!', ['data' => $redirect]);

            return $redirect;
        } catch (\Throwable $th) {
            Log::error('[RedirectRepository - updateRedirect] An error occurred while trying to update the redirect', ['error' => $th->getMessage()]);

            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to update the redirect',
                'error' => $th->getMessage()
            ];
        }
    }

    public function deleteRedirect(string $code): array
    {
        try {
            $id = Hashids::decode($code);

            $redirect = Redirect::find($id[0]);
            if (empty($redirect)) {
                Log::error('[RedirectRepository - deleteRedirect] Redirect not found!', ['code' => $code]);

                return [
                    'status' => 'error',
                    'message' => 'Redirect not found!'
                ];
            }

            $redirect->delete();

            Log::info('[RedirectRepository - deleteRedirect] Redirect deleted successfully!', ['data' => $redirect]);

            return [
                'status' => 'success',
                'message' => 'Redirect deleted successfully!'
            ];
        } catch (\Throwable $th) {
            Log::error('[RedirectRepository - deleteRedirect] An error occurred while trying to delete the redirect', ['error' => $th->getMessage()]);

            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to delete the redirect',
                'error' => $th->getMessage()
            ];
        }
    }
}
