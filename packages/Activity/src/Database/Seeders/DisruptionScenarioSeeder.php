<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisruptionScenarioSeeder extends Seeder
{
    public function run()
    {

        $organizationId = Organization::whereRaw("code = 'ENCODA'")->first()->id;

        $disruptionScenarios = [
            "Loss of Personnel",
            "Loss of IT Systems",
            "Denial of Access to Site",
            "Loss of Utilities or Equipment",
            "Critical Supplier Disruption",
        ];

        $data = array_map(function ($disruptionScenario) use ($organizationId) {
            return [

                'name'            => $disruptionScenario,
                'description'     => $disruptionScenario,
                'organization_id' => $organizationId,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ];

        }, $disruptionScenarios);


        DB::table('disruption_scenarios')->insert($data);
    }
}
