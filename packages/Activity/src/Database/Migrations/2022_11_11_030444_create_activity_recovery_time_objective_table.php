<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected string $tableName = 'activity_recovery_time';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->foreignId('activity_id');
            $table->foreignId('recovery_time_id')->nullable();
            $table->boolean('is_rto_tested')->default(true);
            $table->timestamps();

            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('recovery_time_id')
                ->references('id')
                ->on('recovery_times')
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
