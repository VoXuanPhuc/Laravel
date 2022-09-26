<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected string $tableName = 'resources';

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
                $table->uuid('uid')->default(DB::raw('(UUID())'))->unique();
                $table->string('name');
                $table->string('description');
                $table->tinyInteger('status');
                $table->foreignId('organization_id')->nullable(true);
                $table->foreignId('resources_category_id')->nullable(false);

                $table->foreign('resources_category_id')
                    ->references('id')
                    ->on('resources')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->softDeletesTz();
                $table->timestamps();

                // Indexes
                $table->index('uid');
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
