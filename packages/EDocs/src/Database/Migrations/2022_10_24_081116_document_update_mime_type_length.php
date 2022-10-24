<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected const TABLE = 'documents';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable(self::TABLE)){
            Schema::table(self::TABLE, function (Blueprint $table){
                $table->string('mime_type', 511)->nullable()->change();
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
        if(Schema::hasTable(self::TABLE)){
            Schema::table(self::TABLE, function (Blueprint $table){
                $table->string('mime_type', 50)->nullable()->change();
            });
        }
    }
};
