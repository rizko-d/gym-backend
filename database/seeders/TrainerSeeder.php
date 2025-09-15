<?php

namespace Database\Seeders;

use App\Models\Trainer;
use Illuminate\Database\Seeder;

class TrainerSeeder extends Seeder
{
    public function run()
    {
        $trainers = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@gym.com',
                'phone' => '+1234567890',
                'specializations' => ['Cardio', 'Weight Training', 'HIIT'],
                'experience_years' => 5,
                'bio' => 'Certified personal trainer with 5 years of experience in fitness training.',
                'hourly_rate' => 50.00,
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@gym.com',
                'phone' => '+1234567891',
                'specializations' => ['Yoga', 'Pilates', 'Meditation'],
                'experience_years' => 8,
                'bio' => 'Yoga instructor and wellness coach specializing in mind-body connection.',
                'hourly_rate' => 60.00,
            ],
            [
                'first_name' => 'Mike',
                'last_name' => 'Davis',
                'email' => 'mike.davis@gym.com',
                'phone' => '+1234567892',
                'specializations' => ['CrossFit', 'Strength Training', 'Olympic Lifting'],
                'experience_years' => 7,
                'bio' => 'CrossFit Level 2 trainer with expertise in Olympic weightlifting.',
                'hourly_rate' => 70.00,
            ],
        ];

        foreach ($trainers as $trainer) {
            Trainer::create($trainer);
        }
    }
}
