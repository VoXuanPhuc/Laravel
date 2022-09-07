<?php

namespace Encoda\Organization\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{

    public function run(){

        $encodaOrg = Organization::whereRaw("code = 'ENCODA'")->first();

        DB::table('divisions')->insert([
            [
                'name' => 'IT Department',
                'description' => 'IT Department for Encoda',
                'organization_id' => $encodaOrg->id,
                'avatar_color' => '#ff5252',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Accounting',
                'description' => 'Accounting Department',
                'organization_id' => $encodaOrg->id,
                'avatar_color' => '#f48fb1',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Human Resources',
                'description' => 'Human Resources',
                'organization_id' => $encodaOrg->id,
                'avatar_color' => '#e040fb',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
