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
}
