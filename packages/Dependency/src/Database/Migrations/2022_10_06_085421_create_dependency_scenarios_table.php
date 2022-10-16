<?php

use Encoda\Dependency\Enums\DependencyScenarioStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected string $tableName = 'dependency_scenarios';

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
                $table->string('description', 1023)->nullable(true);
                $table->tinyInteger('status' )->default( DependencyScenarioStatusEnum::CREATED->value );
                $table->boolean('is_active')->default(true);

                $table->foreignId('organization_id')->nullable(true);
                $table->foreign('organization_id')
                    ->references('id') // organization_id
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
        if (Schema::hasTable($this->tableName)) {
            Schema::dropIfExists('dependency_scenarios');
        }
    }
};
