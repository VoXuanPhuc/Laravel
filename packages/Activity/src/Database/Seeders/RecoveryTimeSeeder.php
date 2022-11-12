<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecoveryTimeSeeder extends Seeder
{
    public function run() {

        $organizationId = Organization::whereRaw("code = 'ENCODA'")->first()->id;

        $recoveryTimes = [
            "Immediate (<2 hours).",
            "2 Hours",
            "4 Hours",
            "6 Hours",
            "8 Hours",
            "12 Hours",
            "24 Hours",
            "2 Days",
            "3 Days",
            "1 Week",
            "2 Week",
            "4 Weeks",
            ">4 Weeks",
        ];

        $data = array_map( function( $recoveryTime ) use ( $organizationId ) {
            return [
                'name' => $recoveryTime,
                'description' => $recoveryTime,
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

        }, $recoveryTimes );


        DB::table('recovery_times')->insert( $data );
    }
}
