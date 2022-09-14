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
                'name' => 'Role 1',
                'label' => 'Role 1',
                'description' => 'Role 1',
                'guard_name' => 'api 1',
            ],
            [
                'uid' => '5b9d450e-333d-11ed-aff3-040300000000',
                'tenant_id' => 'ddk6m6d88dbfo1k8tr1u35kot_2',
                'name' => 'Role 2',
                'label' => 'Role 2',
                'description' => 'Role 2',
                'guard_name' => 'api 2',
            ],
            [
                'uid' => '5b9d4671-333d-11ed-aff3-040300000000',
                'tenant_id' => 'ddk6m6d88dbfo1k8tr1u35kot_3',
                'name' => 'Role 3',
                'label' => 'Role 3',
                'description' => 'Role 3',
                'guard_name' => 'api 3',
            ],
            [
                'uid' => '5b9d4712-333d-11ed-aff3-040300000000',
                'tenant_id' => 'ddk6m6d88dbfo1k8tr1u35kot_4',
                'name' => 'Role 4',
                'label' => 'Role 4',
                'description' => 'Role 4',
                'guard_name' => 'api 4',
            ],
        ]);
    }
}
