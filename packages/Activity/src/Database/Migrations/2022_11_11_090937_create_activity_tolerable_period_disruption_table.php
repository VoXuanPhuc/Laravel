<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $tableName = 'activity_tolerable_period_disruption';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if( !Schema::hasTable( $this->tableName ) ) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->foreignId('activity_id');
                $table->foreignId('tolerable_period_disruption_id');
                $table->string('dependent_time', '1023');
                $table->text('reason_choose_dependent_time');
                $table->timestamps();

                $table->foreign('activity_id')
                    ->references('id')
                    ->on('activities')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreign('tolerable_period_disruption_id', 'tpd_id_idx')
                    ->references('id')
                    ->on('tolerable_period_disruptions')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
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
        if( Schema::hasTable( $this->tableName ) ) {
            Schema::drop( $this->tableName );
        }
    }
};
