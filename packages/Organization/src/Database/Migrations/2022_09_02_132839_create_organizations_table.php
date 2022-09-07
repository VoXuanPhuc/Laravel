<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableName = 'organizations';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->uuid( 'uid')->default(\Illuminate\Support\Facades\DB::raw('(UUID())'))
                ->unique()
                ->index('idx_org_uid')
            ;
            $table->string('name');
            $table->string('code')->unique()->index('idx_org_code');
            $table->string('description')->nullable(true);
            $table->string('logo_path')->nullable(true);
            $table->string('friendly_url')->unique();
            $table->string('custom_domain')->unique()->nullable(true);
            $table->string('address')->nullable(true);
            $table->boolean('is_archived')->nullable(true)->default(false);
            $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('organizations');
    }
};
