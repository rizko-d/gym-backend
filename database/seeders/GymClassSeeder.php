<?php

namespace Database\Seeders;

use App\Models\GymClass;
use Illuminate\Database\Seeder;

class GymClassSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            [
                'name' => 'Morning Yoga',
                'description' => 'Start your day with a relaxing yoga session',
                'duration_minutes' => 60,
                'max_participants' => 15,
                'price' => 25.00,
                'difficulty_level' => 'beginner',
                'equipment_needed' => ['Yoga Mat', 'Blocks'],
            ],
            [
                'name' => 'HIIT Cardio',
                'description' => 'High-intensity interval training for maximum fat burn',
                'duration_minutes' => 45,
                'max_participants' => 20,
                'price' => 30.00,
                'difficulty_level' => 'intermediate',
                'equipment_needed' => ['Dumbbells', 'Resistance Bands'],
            ],
            [
                'name' => 'CrossFit WOD',
                'description' => 'Workout of the Day - constantly varied functional movements',
                'duration_minutes' => 60,
                'max_participants' => 12,
                'price' => 40.00,
                'difficulty_level' => 'advanced',
                'equipment_needed' => ['Barbells', 'Kettlebells', 'Pull-up Bar'],
            ],
            [
                'name' => 'Pilates Core',
                'description' => 'Strengthen your core with focused Pilates exercises',
                'duration_minutes' => 50,
                'max_participants' => 10,
                'price' => 35.00,
                'difficulty_level' => 'intermediate',
                'equipment_needed' => ['Pilates Mat', 'Resistance Ring'],
            ],
        ];

        foreach ($classes as $class) {
            GymClass::create($class);
        }
    }
}
