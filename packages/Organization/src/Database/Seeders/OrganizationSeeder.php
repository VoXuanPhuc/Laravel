<?php

namespace Encoda\Organization\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\OrganizationOwner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{

    public function run() {

        DB::table('organizations')->insert([
            [
                'name' => 'Encoda',
                'code' => 'ENCODA',
                'description' => 'ENCODA ASSISTS ORGANISATIONS TO OVERCOME DELIVERY AND PLATFORM MANAGEMENT CHALLENGES.',
                'logo_path' => 'https://amazbin.com/images/encoda.png',
                'friendly_url' => 'encoda.readybc.com',
                'address' => 'Suite 2, Level 5, 459 Little Collins St Melbourne Victoria',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Amazbin',
                'code' => 'Amz',
                'logo_path' => 'https://amazbin.com/images/amz-logo-light.png',
                'description' => '',
                'friendly_url' => 'amz.readybc.com',
                'address' => 'Suite 2, Level 5, 459 Little Collins St Melbourne Victoria',
                'created_at' => Carbon::now(),

            ],
            [
                'name' => 'Google',
                'code' => 'GOOGLE',
                'description' => '',
                'logo_path' => 'https://www.google.com/logos/doodles/2015/googles-new-logo-5078286822539264.3-hp2x.gif',
                'friendly_url' => 'google.readybc.com',
                'address' => '1600 Amphitheatre Parkway, Mountain View, California.',
                'created_at' => Carbon::now(),

            ],
            [
                'name' => 'Facebook',
                'code' => 'FACEBOOK',
                'description' => '',
                'logo_path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Facebook_f_logo_%282019%29.svg/2048px-Facebook_f_logo_%282019%29.svg.png',
                'friendly_url' => 'facebook.readybc.com',
                'address' => '1 Hacker Way Menlo Park, CA 94025',
                'created_at' => Carbon::now(),

            ],
        ]);
    }
}
