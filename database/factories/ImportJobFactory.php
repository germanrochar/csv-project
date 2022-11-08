<?php

namespace Database\Factories;

use App\Models\ImportJob;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $statuses = ImportJob::STATUSES;

        return [
            'job_id' => $this->faker->uuid,
            'uuid' => $this->faker->uuid,
            'status' => $statuses[$this->faker->numberBetween(0, 2)],
            'error_message' => null
        ];
    }
}
