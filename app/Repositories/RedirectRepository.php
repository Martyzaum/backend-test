<?php

namespace App\Repositories;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class RedirectRepository
{
    public function __construct(private Redirect $redirectModel)
    {
    }

    /**
     * Save a redirect
     * @param string $destinyUrl
     * @return array | Redirect
     */
    public function save(string $destinyUrl): array | Redirect
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

    /**
     * Get a redirect
     * @param string $code
     * @return array | Redirect
     */
    public function get(string $code): array | Redirect
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

            $redirect = Cache::remember('redirect_' . $id[0], 60, function () use ($id) {
                return Redirect::find($id[0]);
            });

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

    /**
     * Update a redirect
     * @param string $code
     * @param array $data
     * @return array | Redirect
     */
    public function update(string $code, array $data): array | Redirect
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

            Cache::forget('redirect_' . $id[0]);

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

    /**
     * Delete a redirect
     * @param string $code
     * @return array
     */
    public function delete(string $code): array
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

            Cache::forget('redirect_' . $id[0]);

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

    /**
     * Get all redirects
     * @return array | Collection
     */
    public function getAll(): array | Collection
    {
        try {
            $redirects = Redirect::all();

            Log::info('[RedirectRepository - getAllRedirects] Redirects found successfully!', ['data' => $redirects]);

            return $redirects;
        } catch (\Throwable $th) {
            Log::error('[RedirectRepository - getAllRedirects] An error occurred while trying to get the redirects', ['error' => $th->getMessage()]);

            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to get the redirects',
                'error' => $th->getMessage()
            ];
        }
    }

}
