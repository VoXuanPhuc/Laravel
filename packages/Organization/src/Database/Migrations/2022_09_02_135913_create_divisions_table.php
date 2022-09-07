<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = 'divisions';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->uuid( 'uid')->default(\Illuminate\Support\Facades\DB::raw('(UUID())'))
                ->unique()
                ->index('idx_division_uid');
            $table->string('name')->nullable(false);
            $table->string('description');
            $table->string('avatar_color')->nullable(true);
            $table->integer('is_active')->nullable(false)->default(1);
            $table->foreignId('organization_id');

            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('divisions');
    }
};
