<?php

namespace Encoda\Activity\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    public function run() {
        DB::table('devices')->insert([
            [
                'name' => 'Laptop',
                'description' => 'Laptop device'
            ],
            [
                'name' => 'Computer',
                'description' => 'Computer device'
            ],
            [
                'name' => 'Smart phone',
                'description' => 'Smart phone device'
            ],
        ]);
    }
}
