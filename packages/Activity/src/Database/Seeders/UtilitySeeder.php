<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilitySeeder extends Seeder
{
    public function run() {
        DB::table('utilities')->insert([
            [
                'uid' => '5ba38d9d-333d-11ed-aff3-040300000000',
                'name' => 'Internet',
                'description' => 'Internet utility',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba38e2c-333d-11ed-aff3-040300000000',
                'name' => 'Power',
                'description' => 'Power utility',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba38f05-333d-11ed-aff3-040300000000',
                'name' => 'Gas',
                'description' => 'Gas utility',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
