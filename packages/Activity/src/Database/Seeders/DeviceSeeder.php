<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    public function run() {
        DB::table('devices')->insert([
            [
                'uid' => '5ba2ae1d-333d-11ed-aff3-040300000000',
                'name' => 'Laptop',
                'description' => 'Laptop device',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba2b3c2-333d-11ed-aff3-040300000000',
                'name' => 'Computer',
                'description' => 'Computer device',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba2b668-333d-11ed-aff3-040300000000',
                'name' => 'Smart phone',
                'description' => 'Smart phone device',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
