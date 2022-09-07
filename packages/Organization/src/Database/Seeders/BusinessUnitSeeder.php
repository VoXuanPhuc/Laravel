<?php

namespace Encoda\Organization\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\Division;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessUnitSeeder extends Seeder
{

    public function run() {

        $encodaOrg = Organization::whereRaw("code = 'ENCODA'")->first();
        $division = Division::whereRaw("name ='IT Department'")->first();

        DB::table('business_units')->insert([
            [
                'name' => 'First BU',
                'description' => 'First BU for Encoda',
                'division_id' => $division->id,
                'organization_id' => $encodaOrg->id,
                'avatar_color' => '#ff5252',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Second BU',
                'description' => 'Second BU for Encoda',
                'division_id' => $division->id,
                'organization_id' => $encodaOrg->id,
                'avatar_color' => '#f48fb1',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}

