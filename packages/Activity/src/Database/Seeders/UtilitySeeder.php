<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilitySeeder extends Seeder
{
    public function run() {
        $organizationId = Organization::whereRaw("code = 'ENCODA'")->first()->id;

        $utilities = [
            'Power',
            'Water',
            'Air',
            'Gas',
            'Internet',
            'Fuel (Petrol)',
            'Fuel (Diesel)',
        ];

        $data = array_map( function ( $utility ) use ( $organizationId ) {
            return [
                'name' => $utility,
                'description' => "{$utility} Utility",
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

        }, $utilities );

        DB::table('utilities')->insert( $data );
    }
}
