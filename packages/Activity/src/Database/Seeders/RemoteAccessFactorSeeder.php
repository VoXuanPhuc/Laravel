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

        $remoteAccessFactors = [
            'VPN',
            '2FA Token',
        ];

        $data = array_map( function( $rfa ) use ( $organizationId ) {
            return [

                'name' => $rfa,
                'description' => $rfa,
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

        }, $remoteAccessFactors );


        DB::table('remote_access_factors')->insert( $data );
    }
}
