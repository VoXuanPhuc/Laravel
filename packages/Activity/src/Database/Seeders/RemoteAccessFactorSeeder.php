<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoteAccessFactorSeeder extends Seeder
{
    public function run() {

        $organizationId = Organization::whereRaw("code = 'ENCODA'")->first()->id;

        DB::table('remote_access_factors')->insert([
            [
                'uid' => '5ba34077-333d-11ed-aff3-040300000000',
                'name' => 'Internet',
                'description' => 'Remote access via internet',
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba34125-333d-11ed-aff3-040300000000',
                'name' => 'Banking token',
                'description' => 'Remote access via Banking token',
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid' => '5ba34209-333d-11ed-aff3-040300000000',
                'name' => 'VPN',
                'description' => 'Remote access via VPN',
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
