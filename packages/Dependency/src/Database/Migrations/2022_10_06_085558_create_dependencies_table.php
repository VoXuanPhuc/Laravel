<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $tableName = 'dependencies';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->uuid('uid')->default(DB::raw('(UUID())'))->unique();
                $table->morphs('object');

                $table->foreignId('organization_id')->nullable(true);
                $table->foreign('organization_id')
                    ->references('id') // organization_id
                    ->on('organizations')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreignId('dependency_scenario_id')->nullable(true);
                $table->foreign('dependency_scenario_id')
                    ->references('id')
                    ->on('dependency_scenarios')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->timestampsTz();

                // Indexes
                $table->index('uid');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependencies');
    }
};
