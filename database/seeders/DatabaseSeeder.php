<?php

namespace Database\Seeders;

use Encoda\Activity\Database\Seeders\ActivitySeeder;
use Encoda\Organization\Database\Seeders\OrganizationModuleSeeder;
use Encoda\Rbac\Database\Seeders\RbacSeeder;
use Encoda\Rbac\Database\Seeders\RoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            OrganizationModuleSeeder::class,
            RbacSeeder::class,
            ActivitySeeder::class,
        ]);
    }
}
