<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = 'business_units';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable($this->table)){
            Schema::table($this->table, static function (Blueprint $table){
                $table->foreignId('division_id')->nullable()->change();
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
        if(Schema::hasTable($this->table)){
            Schema::table($this->table, static function (Blueprint $table){
                $table->foreignId('division_id')->nullable(false)->change();
            });
        }
    }
};
