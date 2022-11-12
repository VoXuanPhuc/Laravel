<?php

namespace Encoda\Activity\Database\Seeders;

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run() {
        $this->call(
            [
                ApplicationSeeder::class,
                EquipmentSeeder::class,
                RemoteAccessFactorSeeder::class,
                UtilitySeeder::class,
                TolerableTimePeriodSeeder::class,
                DisruptionScenarioSeeder::class,
                RecoveryTimeSeeder::class,
            ]
        );
    }
}
