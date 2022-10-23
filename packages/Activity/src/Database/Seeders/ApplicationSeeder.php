<?php

namespace Encoda\Activity\Database\Seeders;

use Carbon\Carbon;
use Encoda\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    public function run() {

        $organizationId = Organization::whereRaw("code = 'ENCODA'")->first()->id;

        $apps = [
            'MS Office / O365 (Excel, Word, PowerPoint, Publisher, browser etc)',
            'Outlook',
            'Adobe',
            'PowerBI',
            'SharePoint',
            'Dropbox',
            'Microsoft Teams',
            'Zoom',
            'Authenticator',
        ];

        $data = array_map( function ( $app ) use ( $organizationId ) {
            return [
                'name' => $app,
                'description' => $app,
                'organization_id' => $organizationId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

        }, $apps );

        DB::table('applications')->insert( $data );
    }
}
