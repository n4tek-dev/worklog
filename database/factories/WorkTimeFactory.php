<?php

namespace Database\Factories;

use App\Models\WorkTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WorkTimeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkTime::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = Carbon::instance($this->faker->dateTimeThisMonth);
        $end = (clone $start)->addHours(rand(1, 12));

        return [
            'employee_id' => (string) Str::uuid(),
            'start_time' => $start,
            'end_time' => $end,
            'start_day' => $start->toDateString(),
        ];
    }
}