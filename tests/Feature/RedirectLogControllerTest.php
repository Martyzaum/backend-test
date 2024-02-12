<?php

namespace Tests\Feature;

use App\Models\Redirect;
use App\Models\RedirectLogs;
use Carbon\Carbon;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class RedirectLogsControllerTest extends TestCase
{

    public function test_stats_works()
    {
        $redirect = Redirect::factory()->create([
            'destiny_url' => "https://www.google.com",
        ]);

        for ($i = 0; $i < 10; $i++) {
            RedirectLogs::factory()->create([
                'redirect_id' => $redirect->id,
                'accessed_at' => Carbon::now()
            ]);
        }

        $response = $this->get("/api/redirects/{$redirect->code}/stats");

        $response->assertStatus(200);
    }

    public function test_status_error()
    {
        $response = $this->get("/api/redirects/invalid/stats");

        $response->assertStatus(400);
    }

    public function test_same_ip_is_counted_as_single_access()
    {
        $redirect = Redirect::factory()->create([
            'destiny_url' => "https://www.google.com",
        ]);
        for ($i = 0; $i < 10; $i++) {
            RedirectLogs::factory()->create([
                'redirect_id' => $redirect->id,
                'ip' => '1.0.0.1',
                'accessed_at' => Carbon::now()
            ]);
        }

        $response = $this->get("/api/redirects/{$redirect->code}/stats");

        $response->assertJson([
            'status' => 'success',
            'message' => 'Stats retrieved successfully!',
            'data' => [
                'total_accesses' => 10,
                'unique_accesses' => 1,
            ]
        ]);
    }
}
