<?php

namespace Encoda\Activity\Database\Seeders;

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run() {
        $this->call(
            [
                ApplicationSeeder::class,
                DeviceSeeder::class,
                RemoteAccessSeeder::class,
                UtilitySeeder::class,
            ]
        );
    }
}
