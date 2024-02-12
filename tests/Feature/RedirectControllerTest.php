<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Redirect;

class RedirectControllerTest extends TestCase
{
    private $code = 'mO';

    public function test_store_working()
    {
        $response = $this->post('/api/redirects/', [
            'destiny_url' => 'https://www.google.com',
        ]);

        $response->assertStatus(201);
    }

    public function test_store_fail_with_invalid_dns()
    {
        $response = $this->post('/api/redirects/', [
            'destiny_url' => 'https://www.google.com.invalid',
        ]);

        $response->assertStatus(400);
    }

    public function test_store_fail_with_invalid_url()
    {
        $response = $this->post('/api/redirects/', [
            'destiny_url' => 'invalid',
        ]);

        $response->assertStatus(400);
    }

    public function test_store_fail_with_self_url()
    {
        $response = $this->post('/api/redirects/', [
            'destiny_url' => 'https://localhost:8000/api/redirects/',
        ]);

        $response->assertStatus(400);
    }

    public function test_store_fail_with_http_url()
    {
        $response = $this->post('/api/redirects/', [
            'destiny_url' => 'http://www.google.com',
        ]);

        $response->assertStatus(400);
    }

    public function test_store_fail_with_url_returning_status_different_from_200_or_201()
    {

        $response = $this->post('/api/redirects/', [
            'destiny_url' => 'https://httpstat.us/500',
        ]);

        $response->assertStatus(400);
    }

    public function test_store_fail_with_invalid_url_because_it_has_query_params_with_empty_key()
    {
        $response = $this->post('/api/redirects/', [
            'destiny_url' => 'https://www.google.com?param1=&param2=value2',
        ]);

        $response->assertStatus(400);
    }

    public function test_index_working()
    {
        $response = $this->get('/api/redirects/' . $this->code);

        $response->assertStatus(200);
    }

    public function test_index_fail_with_invalid_code()
    {
        $response = $this->get('/api/redirects/invalid');

        $response->assertStatus(404);
    }

    public function test_show_working()
    {
        $response = $this->get('/api/redirects/' . $this->code);

        $response->assertStatus(200);
    }

    public function test_show_fail_with_invalid_code()
    {
        $response = $this->get('/api/redirects/invalid');

        $response->assertStatus(404);
    }

    public function test_update_url_working()
    {
        $response = $this->put('/api/redirects/' . $this->code, [
            'destiny_url' => 'https://google.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_update_status_working()
    {
        $response = $this->put('/api/redirects/' . $this->code, [
            'status' => 'active',
        ]);

        $response->assertStatus(200);
    }

    public function test_update_fail_with_inexistent_code()
    {
        $response = $this->put('/api/redirects/invalid', [
            'destiny_fail' => 'https://google.com',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_fail_with_invalid_dns()
    {
        $response = $this->put('/api/redirects/' . $this->code, [
            'destiny_url' => 'https://www.x.com.invalid',
        ]);

        $response->assertStatus(400);
    }

    public function test_update_fail_with_invalid_url()
    {
        $response = $this->put('/api/redirects/' . $this->code, [
            'destiny_url' => 'invalid',
        ]);

        $response->assertStatus(400);
    }

    public function test_update_fail_with_self_url()
    {
        $response = $this->put('/api/redirects/' . $this->code, [
            'destiny_url' => 'https://localhost:8000/api/redirects/' . $this->code,
        ]);

        $response->assertStatus(400);
    }

    public function test_update_fail_with_http_url()
    {
        $response = $this->put('/api/redirects/' . $this->code, [
            'destiny_url' => 'http://www.google.com',
        ]);

        $response->assertStatus(400);
    }

    public function test_update_fail_with_url_returning_status_different_from_200_or_201()
    {
        $response = $this->put('/api/redirects/' . $this->code, [
            'destiny_url' => 'https://httpstat.us/500',
        ]);

        $response->assertStatus(400);
    }

    public function test_update_fail_with_invalid_url_because_it_has_query_params_with_empty_key()
    {
        $response = $this->put('/api/redirects/' . $this->code, [
            'destiny_url' => 'https://www.google.com?param1=&param2=value2',
        ]);

        $response->assertStatus(400);
    }

    public function test_destroy_working()
    {
        $response = $this->delete('/api/redirects/' . $this->code);

        $response->assertStatus(204);
    }

    public function test_destroy_fail_with_inexistent_code()
    {
        $response = $this->delete('/api/redirects/invalid');

        $response->assertStatus(404);
    }
}
