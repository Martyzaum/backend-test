<?php

namespace Database\Factories;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Factories\Factory;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Redirect>
 */
class RedirectFactory extends Factory
{
    protected $model = Redirect::class;

    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'destiny_url' => $this->faker->url,
            'last_access' => $this->faker->dateTimeThisYear,
            'deleted_at' => $this->faker->randomElement([null, $this->faker->dateTimeThisYear]),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Redirect $redirect) {
            $redirect->code = Hashids::connection('main')->encode($redirect->id);
            $redirect->save();
        });
    }
}
