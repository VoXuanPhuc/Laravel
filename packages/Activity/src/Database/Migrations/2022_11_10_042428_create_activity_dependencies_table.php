<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected string $tableName = 'activity_dependency_scenario';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();

                $table->foreignId('activity_id');
                $table->foreignId('dependency_scenario_id');

                $table->foreign('activity_id')
                    ->references('id')
                    ->on('activities')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreign('dependency_scenario_id')
                    ->references('id')
                    ->on('dependency_scenarios')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->timestamps();
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
        Schema::dropIfExists($this->tableName);
    }
};
