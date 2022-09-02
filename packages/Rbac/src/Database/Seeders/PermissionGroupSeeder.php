<?php

namespace Encoda\Rbac\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionGroupSeeder extends Seeder
{

    public function run() {
        DB::table('permission_groups')->insert([
            [
                'name' => 'User',
                'description' => 'User permission group'
            ],
            [
                'name' => 'Organization',
                'description' => 'Organization permission group'
            ],
            [
                'name' => 'Division',
                'description' => 'Division permission group'
            ],
            [
                'name' => 'Business Unit',
                'description' => 'Business Unit permission group'
            ],
        ]);
    }
}
