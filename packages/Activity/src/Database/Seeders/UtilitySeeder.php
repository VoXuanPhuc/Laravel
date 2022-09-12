<?php

namespace Encoda\Activity\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilitySeeder extends Seeder
{
    public function run() {
        DB::table('utilities')->insert([
            [
                'name' => 'Internet',
                'description' => 'Internet utility'
            ],
            [
                'name' => 'Power',
                'description' => 'Power utility'
            ],
            [
                'name' => 'Gas',
                'description' => 'Gas utility'
            ],
        ]);
    }
}
