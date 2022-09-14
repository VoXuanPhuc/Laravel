<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    public function run() {
        DB::table('applications')->insert([
            [
                'uid' => '5ba20994-333d-11ed-aff3-040300000000',
                'name' => 'NAB Internet Banking',
                'description' => 'NAB Internet Banking software',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba20a43-333d-11ed-aff3-040300000000',
                'name' => 'Software package 2',
                'description' => 'Software package 2 description',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba20b22-333d-11ed-aff3-040300000000',
                'name' => 'Software package 3',
                'description' => 'Software package 3 description',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
