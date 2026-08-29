<?php

namespace Database\Factories;

use App\Models\Work;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkFactory extends Factory
{
    protected $model = Work::class;

    public function definition(): array
    {
        return [
            'name_of_applicant' => $this->faker->name(),
            'number_of_applicants' => 1,
            'assignment_date' => now(),
            'status' => 'New File',
            'payment_status' => 'Payment Due',
            'delivery_status' => 'Delivery Due',
            'work_type' => 'valuation',
            'created_by' => User::factory(),
        ];
    }
}
