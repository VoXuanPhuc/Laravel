<?php

namespace Encoda\Organization\Database\Seeders;

use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationOwnerSeeder extends Seeder
{

    public function run() {
        DB::table('organization_owners')->insert([
            [
                'first_name' => 'Mo and Matt',
                'last_name' => 'Test',
                'email' => 'contact@encoda.io',
                'phone' => '+61 433 238 082',
                'organization_id' => Organization::whereRaw("code = 'ENCODA'")->first()->id,
            ],
            [
                'first_name' => 'Nam',
                'last_name' => 'Test',
                'email' => 'contact@amazbin.com',
                'phone' => '+84 8888 06 196',
                'organization_id' => Organization::whereRaw("code = 'Amz'")->first()->id,
            ],
            [
                'first_name' => 'Larry',
                'last_name' => 'Page',
                'email' => 'contact@google.com',
                'phone' => '+1 8888 06 196',
                'organization_id' => Organization::whereRaw("code = 'GOOGLE'")->first()->id,
            ],
            [
                'first_name' => 'Mark',
                'last_name' => 'Test',
                'email' => 'contact@facebook.com',
                'phone' => '+1 8888 06 196',
                'organization_id' => Organization::whereRaw("code = 'FACEBOOK'")->first()->id,
            ],
        ]);
    }
}
