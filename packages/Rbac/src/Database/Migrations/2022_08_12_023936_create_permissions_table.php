<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }
        if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
            throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id'); // permission id
            $table->uuid( 'uid')->default(DB::raw('(UUID())'))->unique();
            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('label');
            $table->string('description')->nullable(true);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->foreignId('group_id')->nullable(true);

            $table->foreign('group_id')
                ->references('id')
                ->on('permission_groups')
                ->cascadeOnUpdate()
                ->nullOnDelete();


            $table->timestamps();
            $table->softDeletesTz();

            $table->unique(['name', 'guard_name']);

            // Indexes
            $table->index('uid');
        });

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $tableNames = config('permission.table_names');
        if( Schema::hasTable( $tableNames['permissions'] ) ) {
            Schema::drop( $tableNames['permissions'] );
        }
    }
};
