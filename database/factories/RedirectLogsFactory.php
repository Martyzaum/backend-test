<?php

namespace Database\Factories;

use App\Models\RedirectLogs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RedirectLogs>
 */
class RedirectLogsFactory extends Factory
{
    protected $model = RedirectLogs::class;

    public function definition()
    {
        return [
            'redirect_id' => $this->faker->randomElement([1, 1, 1, 2, 3, 3, 4, 5, 6, 6, 7, 8, 8, 9, 10]),
            'ip' => $this->faker->randomElement([$this->faker->ipv4, $this->faker->ipv4, $this->faker->ipv4, $this->faker->ipv4, $this->faker->ipv4, '1.0.2.27']),
            'user_agent' => $this->faker->userAgent,
            'referer' => $this->faker->url,
            'query_params' => $this->faker->randomElement([null, null, null, null, $this->faker->sentence, json_encode(['param' => $this->faker->word])]),
            'accessed_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
