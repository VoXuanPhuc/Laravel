<?php

namespace Encoda\Rbac\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run() {
        DB::table('roles')->insert([
            [
                'uid' => '5b9d431d-333d-11ed-aff3-040300000000',
                'tenant_id' => 'ddk6m6d88dbfo1k8tr1u35kot',
                'name' => 'payroll-manager',
                'label' => 'Payroll Manager',
                'description' => 'Payroll Manager',
                'guard_name' => 'api 1',
            ],
            [
                'uid' => '5b9d450e-333d-11ed-aff3-040300000000',
                'tenant_id' => 'ddk6m6d88dbfo1k8tr1u35kot_2',
                'name' => 'general-manger',
                'label' => 'General Manager',
                'description' => 'General Manager',
                'guard_name' => 'api 2',
            ],
            [
                'uid' => '5b9d4671-333d-11ed-aff3-040300000000',
                'tenant_id' => 'ddk6m6d88dbfo1k8tr1u35kot_3',
                'name' => 'accountant',
                'label' => 'Accountant',
                'description' => 'Accountant',
                'guard_name' => 'api 3',
            ],
            [
                'uid' => '5b9d4712-333d-11ed-aff3-040300000000',
                'tenant_id' => 'ddk6m6d88dbfo1k8tr1u35kot_4',
                'name' => 'administrator',
                'label' => 'Administrator',
                'description' => 'Administrator',
                'guard_name' => 'api 4',
            ],
        ]);
    }
}
