<?php

namespace Database\Factories;

use App\Models\ItemClassification;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemClassificationFactory extends Factory
{
    protected $model = ItemClassification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $classificationNames = [
            'Office Equipment',
            'Furnitures',
            'IT/Software',
            'Comm.Egpt',
            'Tech./Scientific Egpt.',
            'Firefighting Egpt.',
            'Hospital Egpt',
            'Medical, Dental and Lab Egpt',
            'Sport Egpt',
            'Other Machineries & Egpt.',
            'Library Books',
            'Motor Vehicles',
            'Other PPE',
        ];

        // Define classification codes directly without the risk of repetition
        $classificationCode = [
            '221',
            '222',
            '223',
            '229',
            '236',
            '231',
            '232',
            '233',
            '235',
            '240',
            '224',
            '241',
            '250',
        ];

        // Ensure each classification code is unique
        $index = $this->faker->unique()->numberBetween(0, count($classificationNames) - 1);

        return [
            'classification_code' => $classificationCode[$index],
            'classification_name' => $classificationNames[$index], // Pick sequentially from array
            'status' => $this->faker->boolean(80), // Random boolean (80% chance of being true)
        ];
    }
}
