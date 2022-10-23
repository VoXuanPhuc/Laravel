<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder
{
    public function run() {
        $organizationId = Organization::whereRaw("code = 'ENCODA'")->first()->id;
        $equipments = [
            'Laptop / PC',
            'Tablet',
            'Mobile Phone',
            'Landline',
            'Headphones',
            '2FA Token / Authenticator',
            'Car (type - 2WD / 4WD)',
            'Truck (type and number)',
            'Forklift (type and number)',
        ];

        $data = array_map( function( $equipment ) use ( $organizationId ){
            return [

                'name' => $equipment,
                'description' => $equipment,
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

        }, $equipments );

        DB::table('equipments')->insert( $data );
    }
}
