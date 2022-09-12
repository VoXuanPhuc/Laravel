<?php
namespace Encoda\Rbac\Database\Seeders;

class RbacSeeder extends \Illuminate\Database\Seeder
{

    public function run() {
        $this->call(
            [
                PermissionGroupSeeder::class,
                PermissionSeeder::class,
                RoleSeeder::class,
            ]
        );
    }
}
