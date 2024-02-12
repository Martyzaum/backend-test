<?php

namespace Database\Seeders;

use App\Models\RedirectLogs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RedirectsLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RedirectLogs::factory()->count(548)->create();
    }
}
