<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected string $tableName = 'owners_has_resources';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if( !Schema::hasTable( $this->tableName ) ) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->foreignId('resource_id')->nullable(false);
                $table->foreignId('resource_owner_id')->nullable(false);

                $table->foreign('resource_id')
                    ->references('id')
                    ->on('resources')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreign('resource_owner_id')
                    ->references('id')
                    ->on('resource_owners')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->timestamps();
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
