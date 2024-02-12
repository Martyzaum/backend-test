<?php

namespace Database\Seeders;

use App\Models\Redirect;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RedirectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Redirect::factory()->count(10)->create();
    }
}
