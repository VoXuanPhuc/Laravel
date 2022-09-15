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
            $table->integer('number_of_location')->nullable(true);
            $table->tinyInteger('is_remoted');
            $table->tinyInteger('status');
            $table->tinyInteger('step');
            $table->foreignId('division_id');
            $table->foreignId('business_unit_id');
            $table->softDeletesTz();
            $table->timestamps();
    
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
