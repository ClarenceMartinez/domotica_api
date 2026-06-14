<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => 'Basic',    'description' => 'Ideal for a single room.',         'device_limit' => 5],
            ['name' => 'Standard', 'description' => 'For complete homes.',               'device_limit' => 15],
            ['name' => 'Premium',  'description' => 'No limits for large properties.',   'device_limit' => 50],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
