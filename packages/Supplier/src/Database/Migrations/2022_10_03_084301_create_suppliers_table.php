<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $tableName = 'suppliers';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableName)){
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('uid')->default(DB::raw('(UUID())'))->unique();
                $table->string('name');
                $table->string('address', 511)->nullable(true);
                $table->string('description', 1023)->nullable(true);
                $table->string('email', 255)->nullable(true);
                $table->string('fax', 255)->nullable(true);
                $table->string('phone_number', 15)->nullable(true);
                $table->boolean('is_active')->default(true);

                $table->foreignId('organization_id')->nullable(true);

                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->softDeletesTz();
                $table->timestampsTz();

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
        Schema::dropIfExists($this->tableName);
    }
};
