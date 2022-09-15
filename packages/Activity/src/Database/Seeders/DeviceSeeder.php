<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    public function run() {
        $organizationId = Organization::whereRaw("code = 'ENCODA'")->first()->id;
        
        DB::table('devices')->insert([
            [
                'uid' => '5ba2ae1d-333d-11ed-aff3-040300000000',
                'name' => 'Laptop',
                'description' => 'Laptop device',
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba2b3c2-333d-11ed-aff3-040300000000',
                'name' => 'Computer',
                'description' => 'Computer device',
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba2b668-333d-11ed-aff3-040300000000',
                'name' => 'Smart phone',
                'description' => 'Smart phone device',
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
