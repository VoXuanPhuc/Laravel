<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected string $tableName = 'activities';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid( 'uid')->default(DB::raw('(UUID())'))->unique();
            $table->string('name');
            $table->string('description')->nullable(true);
            $table->string('on_site_requires')->nullable(true);
            $table->boolean('is_remote')->default(false);
            $table->integer('min_people')->default(1);
            $table->tinyInteger('status')->default(\Encoda\Activity\Models\Activity::CREATED);
            $table->tinyInteger('step')->default(\Encoda\Activity\Models\Activity::STEP_ACTIVITY_INFO);
            $table->foreignId('organization_id');
            $table->foreignId('division_id')->nullable(true);
            $table->foreignId('business_unit_id')->nullable(true);
            $table->softDeletesTz();
            $table->timestamps();

            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('business_unit_id')
                ->references('id')
                ->on('business_units')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Indexes
            $table->index('uid');
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
