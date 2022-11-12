<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected string $tableName = 'activity_disruption_scenario';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->foreignId('activity_id');
            $table->foreignId('disruption_scenario_id')->nullable();
            $table->text('workaround_solution')->nullable();
            $table->text('workaround_feasibly')->nullable();
            $table->timestamps();

            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('disruption_scenario_id')
                ->references('id')
                ->on('disruption_scenarios')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if( Schema::hasTable( $this->tableName ) ) {
            Schema::drop( $this->tableName );
        }
    }
};
