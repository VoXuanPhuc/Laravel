<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected string $tableName = 'supplier_has_category';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->foreignId('supplier_id')
                    ->nullable(true)
                    ->index('idx_supplier_id');

                $table->foreignId('supplier_category_id')
                    ->nullable(true)
                    ->index('idx_supplier_category_id');

                $table->foreign('supplier_id')
                    ->references('id')
                    ->on('suppliers')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreign('supplier_category_id')
                    ->references('id')
                    ->on('supplier_categories')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->timestampsTz();

                $table->index(['supplier_id', 'supplier_category_id'], 'idx_supplier_has_category');
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
