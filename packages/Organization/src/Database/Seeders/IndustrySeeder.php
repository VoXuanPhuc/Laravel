<?php

namespace Encoda\Organization\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{

    public function run() {
        DB::table('industries')->insert([
            [
                'name' => 'Manufacturing',
                'description' => 'Manufacturing industry',
            ],
            [
                'name' => 'IT',
                'description' => '',
            ],
            [
                'name' => 'Retail',
                'description' => '',
            ],
            [
                'name' => 'Accommodation and Food Services',
                'description' => '',
            ],
            [
                'name' => 'Administration',
                'description' => '',
            ],
            [
                'name' => 'Finance and Insurance',
                'description' => '',
            ],
        ]);
    }
}
