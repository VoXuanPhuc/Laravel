<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected string $tableName = 'applications';

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
            $table->softDeletesTz();
            $table->timestamps();

            // Indexes
            $table->index('uid');

            $table->unique(['name']);
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