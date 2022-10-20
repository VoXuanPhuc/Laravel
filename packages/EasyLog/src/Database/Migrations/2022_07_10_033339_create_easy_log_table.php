<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        $dbConnection = config('easylog.database_connection');
        $tableName = config('easylog.table_name' );

        Schema::connection( $dbConnection )->create( $tableName, function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->string('event')->nullable();
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->timestamps();

            $table->index('log_name');
        });
    }

    public function down()
    {
        $dbConnection = config('easylog.database_connection');
        $tableName = config('easylog.table_name' );

        Schema::connection( $dbConnection )->dropIfExists( $tableName );
    }
};
