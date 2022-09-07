<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = 'organization_with_industries';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {

            $table->foreignId('organization_id')->nullable(true);
            $table->foreignId('industry_id')->nullable(true);

            $table->foreign('organization_id')
                ->references('id') // permission id
                ->on( 'organizations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();


            $table->foreign('industry_id')
                ->references('id') // permission id
                ->on( 'industries')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
};
