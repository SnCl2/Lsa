<?php

namespace Database\Factories;

use App\Models\Inspection;
use App\Models\Work;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionFactory extends Factory
{
    protected $model = Inspection::class;

    public function definition(): array
    {
        return [
            'work_id' => Work::factory(),
            'created_by' => User::factory(),
            'bank_branch' => 'Test Branch',
            'phone_no' => '1234567890',
            'representative' => 'Rep Name',
            'applicant_name' => $this->faker->name(),
            'address' => '123 Test St',
            'property_type' => 'Flat',
            'nature_property' => 'Residential',
            'lift_available' => 'No',
            'garage_available' => 'No',
            'occupied_by' => 'Owner',
            'flooring_type' => 'Tiles',
            'wiring_type' => 'Concealed',
            'plot_demarcated' => 'No',
        ];
    }
}
