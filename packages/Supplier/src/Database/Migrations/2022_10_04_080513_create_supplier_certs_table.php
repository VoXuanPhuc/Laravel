<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected string $tableName = 'supplier_certs';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create('supplier_certs', function (Blueprint $table) {
                $table->id();
                $table->uuid('uid')->default(DB::raw('(UUID())'))->unique();
                $table->string('cert_path', 511);
                $table->foreignId('supplier_id');
                $table->foreign('supplier_id')
                    ->references('id')
                    ->on('suppliers')
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
        if (Schema::hasTable($this->tableName)) {
            Schema::dropIfExists('supplier_certs');
        }
    }
};
