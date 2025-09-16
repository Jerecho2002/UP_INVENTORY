<?php

namespace Database\Factories;

use App\Models\FundSource;
use Illuminate\Database\Eloquent\Factories\Factory;

class FundSourceFactory extends Factory
{
    protected $model = FundSource::class;

    public function definition()
    {
        return [
            'code'        => $this->faker->unique()->lexify('FS-????'),
            'description' => $this->faker->optional()->sentence(),
            'status'      => $this->faker->numberBetween(0, 2),
        ];
    }
}
