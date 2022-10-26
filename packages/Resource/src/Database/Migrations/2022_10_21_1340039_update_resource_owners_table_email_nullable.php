<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected string $tableName = 'resource_owners';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if( Schema::hasTable( $this->tableName ) ) {
            // Add email column null able
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->string('email')->nullable(true)->change();
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
            // Add email column notnull able
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->string('email')->nullable(false)->change();
            });
        }
    }
};
