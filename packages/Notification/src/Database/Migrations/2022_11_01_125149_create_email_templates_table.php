<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected const TABLE = 'email_templates';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable(self::TABLE)){
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid( 'uid')->default(DB::raw('(UUID())'))
                    ->unique()
                    ->index('idx_email_template_uid')
                ;

                $table->foreignId('organization_id');

                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->string('name', 511);
                $table->string('title', 511);
                $table->text('data');
                $table->text('description')->nullable();
                $table->boolean('system_mail')->default(false);

                $table->foreignId('created_by')->nullable();
                $table->foreign('created_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();

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
        Schema::dropIfExists(self::TABLE);
    }
};
