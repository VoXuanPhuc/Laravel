<?php

use Encoda\Dependency\Enums\DependencyTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected string $tableName = "dependency_details";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();

                $table->morphs('dependable');

                $table->enum('dependent_type', array_column(DependencyTypes::cases(), 'value'));

                $table->foreignId('dependency_id')->nullable(true);

                $table->foreign('dependency_id')
                    ->references('id')
                    ->on('dependencies')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

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
        if (Schema::hasTable($this->tableName)) {
            Schema::dropIfExists($this->tableName);
        }
    }
};
