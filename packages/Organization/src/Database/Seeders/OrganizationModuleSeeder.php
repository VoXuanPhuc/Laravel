<?php

namespace Encoda\Organization\Database\Seeders;

use Illuminate\Database\Seeder;

class OrganizationModuleSeeder extends Seeder
{

    public function run() {
        $this->call(
            [
                OrganizationSeeder::class,
                OrganizationOwnerSeeder::class,
                IndustrySeeder::class,
                DivisionSeeder::class,
                BusinessUnitSeeder::class,
            ]
        );
    }
}
