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
        if( !Schema::hasTable( $this->tableName ) ) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('uid')->default(DB::raw('(UUID())'))->unique()->index('idx_uid');
                $table->string('first_name');
                $table->string('last_name');
                $table->integer('is_invite')->default(0);
                $table->string('email');
                $table->foreignId('organization_id')->nullable(true)->index('idx_organization_id');

                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->softDeletesTz();
                $table->timestampsTz();

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
