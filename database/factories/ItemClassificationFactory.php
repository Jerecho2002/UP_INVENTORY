<?php

namespace Database\Factories;

use App\Models\ItemClassification;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemClassificationFactory extends Factory
{
    protected $model = ItemClassification::class;

    public function definition()
    {
        // Optional: you can keep some example names
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

        $classificationCodes = [
            '221', '222', '223', '229', '236', '231', '232', '233', '235', '240', '224', '241', '250',
        ];

        return [
            'classification_code' => $this->faker->randomElement($classificationCodes),
            'classification_name' => $this->faker->randomElement($classificationNames),
            'status' => $this->faker->boolean(80),
        ];
    }
}
