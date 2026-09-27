<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\State;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@tour.ng'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Nigerian States
        $states = [
            ['name' => 'Ogun', 'region' => 'South West'],
            ['name' => 'Niger', 'region' => 'North Central'],
            ['name' => 'Bauchi', 'region' => 'North East'],
            ['name' => 'Lagos', 'region' => 'South West'],
            ['name' => 'Kano', 'region' => 'North West'],
            ['name' => 'Enugu', 'region' => 'South East'],
            ['name' => 'Rivers', 'region' => 'South South'],
            ['name' => 'Plateau', 'region' => 'North Central'],
            ['name' => 'Osun', 'region' => 'South West'],
            ['name' => 'Anambra', 'region' => 'South East'],
            ['name' => 'Akwa Ibom', 'region' => 'South South'],
            ['name' => 'Cross River', 'region' => 'South South'],
            ['name' => 'Ondo', 'region' => 'South West'],
            ['name' => 'Kwara', 'region' => 'North Central'],
            ['name' => 'Abuja FCT', 'region' => 'North Central'],
        ];
        foreach ($states as $state) {
            State::firstOrCreate(['name' => $state['name']], $state);
        }

        // Categories
        $categories = [
            ['name' => 'Nature', 'icon' => '🌿', 'color' => '#22c55e'],
            ['name' => 'Heritage', 'icon' => '🏛️', 'color' => '#D4A017'],
            ['name' => 'Historical', 'icon' => '📜', 'color' => '#8B4513'],
            ['name' => 'Parks & Resorts', 'icon' => '🏕️', 'color' => '#0B3D2E'],
            ['name' => 'Cultural', 'icon' => '🎭', 'color' => '#7c3aed'],
            ['name' => 'Recreational', 'icon' => '🌊', 'color' => '#0ea5e9'],
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        $this->call(AttractionSeeder::class);
    }
}
