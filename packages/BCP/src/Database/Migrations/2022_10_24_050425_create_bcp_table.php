<?php

use Encoda\BCP\Enums\BCPStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $tableName = 'bcp';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('uid')->default(DB::raw('(UUID())'))->unique();
                $table->string('name');
                $table->text('description')->nullable(true);
                $table->enum('status', array_column(BCPStatusEnum::cases(), 'value' ));

                $table->foreignId('organization_id')->nullable(true);
                $table->foreign('organization_id')
                    ->references('id') // organization_id
                    ->on('organizations')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->dateTimeTz('due_date');
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
