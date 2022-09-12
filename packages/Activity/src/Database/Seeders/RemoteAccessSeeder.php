<?php

namespace Encoda\Activity\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoteAccessSeeder extends Seeder
{
    public function run() {
        DB::table('remote_accesses')->insert([
            [
                'name' => 'Internet',
                'description' => 'Remote access via internet'
            ],
            [
                'name' => 'Banking token',
                'description' => 'Remote access via Banking token'
            ],
            [
                'name' => 'VPN',
                'description' => 'Remote access via VPN'
            ],
        ]);
    }
}
