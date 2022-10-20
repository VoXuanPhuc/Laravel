<?php

namespace Encoda\Rbac\Database\Seeders;

use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run() {

        $escalate = Organization::whereRaw("code = 'ESCALATE'")->first();
        $encodaOrg = Organization::whereRaw("code = 'ENCODA'")->first();

        DB::table('roles')->insert([
            [
                'uid' => '5b9d431d-333d-41ed-aff3-040300000000',
                'organization_id' => $escalate->id,
                'name' => 'super-admin',
                'label' => 'Super Admin',
                'description' => 'Admin of everything',
                'guard_name' => 'api',
            ],
            [
                'uid' => '5b9d431d-333d-11ed-aff3-040300000000',
                'organization_id' => $encodaOrg->id,
                'name' => 'payroll-manager',
                'label' => 'Payroll Manager',
                'description' => 'Payroll Manager',
                'guard_name' => 'api',
            ],
            [
                'uid' => '5b9d450e-333d-11ed-aff3-040300000000',
                'organization_id' => $encodaOrg->id,
                'name' => 'general-manger',
                'label' => 'General Manager',
                'description' => 'General Manager',
                'guard_name' => 'api',
            ],
            [
                'uid' => '5b9d4671-333d-11ed-aff3-040300000000',
                'organization_id' => $encodaOrg->id,
                'name' => 'accountant',
                'label' => 'Accountant',
                'description' => 'Accountant',
                'guard_name' => 'api',
            ],
            [
                'uid' => '5b9d4712-333d-11ed-aff3-040300000000',
                'organization_id' => $encodaOrg->id,
                'name' => 'administrator',
                'label' => 'Administrator',
                'description' => 'Administrator',
                'guard_name' => 'api',
            ],
        ]);
    }
}
