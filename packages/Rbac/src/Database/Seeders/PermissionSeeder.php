<?php

namespace Encoda\Rbac\Database\Seeders;

use Encoda\Rbac\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{

    public function run() {

        $userPermissionGroup = PermissionGroup::whereRaw("name = 'User'")->first();
        $orgPermissionGroup = PermissionGroup::whereRaw("name = 'Organization'")->first();
        $divPermissionGroup = PermissionGroup::whereRaw("name = 'Division'")->first();

        DB::table('permissions')->insert([
            // User
            [
                'guard_name' => 'api',
                'name' => 'create-user',
                'label' => 'Create User',
                'description' => 'Allow user to create internal user',
                'group_id' => $userPermissionGroup->id,
            ],
            [
                'guard_name' => 'api',
                'name' => 'update-user',
                'label' => 'Update User',
                'description' => 'Allow user to update internal user',
                'group_id' => $userPermissionGroup->id,
            ],
            [
                'guard_name' => 'api',
                'name' => 'delete-user',
                'label' => 'Delete User',
                'description' => 'Allow user to delete internal user',
                'group_id' => $userPermissionGroup->id,
            ],
            // Org
            [
                'guard_name' => 'api',
                'name' => 'create-organization',
                'label' => 'Create Organization',
                'description' => 'Allow user to create organization',
                'group_id' => $orgPermissionGroup->id,
            ],
            [
                'guard_name' => 'api',
                'name' => 'update-organization',
                'label' => 'Update Organization',
                'description' => 'Allow user to update organization',
                'group_id' => $orgPermissionGroup->id,
            ],
            [
                'guard_name' => 'api',
                'name' => 'delete-organization',
                'label' => 'Delete Organization',
                'description' => 'Allow user to delete organization',
                'group_id' => $orgPermissionGroup->id,
            ],
            // Division
            [
                'guard_name' => 'api',
                'name' => 'create-division',
                'label' => 'Create Division',
                'description' => 'Allow user to create division',
                'group_id' => $divPermissionGroup->id,
            ],
            [
                'guard_name' => 'api',
                'name' => 'update-division',
                'label' => 'Update Division',
                'description' => 'Allow user to update division',
                'group_id' => $divPermissionGroup->id,
            ],
            [
                'guard_name' => 'api',
                'name' => 'delete-division',
                'label' => 'Delete Division',
                'description' => 'Allow user to delete division',
                'group_id' => $divPermissionGroup->id,
            ],
        ]);
    }
}
