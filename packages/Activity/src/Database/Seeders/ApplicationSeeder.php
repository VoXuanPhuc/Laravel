<?php

namespace Encoda\Activity\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    public function run() {
        DB::table('applications')->insert([
            [
                'name' => 'NAB Internet Banking',
                'description' => 'NAB Internet Banking software'
            ],
            [
                'name' => 'Software package 2',
                'description' => 'Software package 2 description'
            ],
            [
                'name' => 'Software package 3',
                'description' => 'Software package 3 description'
            ],
        ]);
    }
}
