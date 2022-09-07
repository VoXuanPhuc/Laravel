<?php

namespace Encoda\Core\Commands;

use Exception;
use Illuminate\Console\Command;

class StorageLink extends Command
{

    protected $signature = 'storage:link';

    protected $description = 'Create symlink from storage/app/public to public/storage';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {

            mkdir( base_path() . '/public/' . env('SERVICE_NAME') );

            $storagePath = base_path() . '/storage/app/public';
            $symlinkPath = base_path() . '/public/' .  env('SERVICE_NAME') . '/storage';

            $rs = symlink( $storagePath, $symlinkPath );

            if( $rs ) {
                $this->info("Created symlink from {$storagePath} to $symlinkPath" );
            }
            else {
                $this->error('Unable to create Storage link');
            }

        } catch(Exception $e) {
            $this->error('Unable to create Storage link: ' . $e->getMessage());
        }
    }
}
