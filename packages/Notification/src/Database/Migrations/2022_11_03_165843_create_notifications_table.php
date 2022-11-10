<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected $table = 'notifications';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists($this->table);
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('type');
            $table->morphs('notifiable');
            $table->string('title');
            $table->text('data');
            $table->boolean('pinned')->nullable();
            $table->timestamp('read_at')->nullable();

            $table->nullableMorphs('modelable');

            $table->foreignId('event_notification_id')->nullable();

            $table->foreign('event_notification_id')
                ->references('id')
                ->on('event_notifications')
                ->cascadeOnUpdate()
                ->nullOnDelete();


            $table->foreignId('organization_id')->nullable();
            $table->foreign('organization_id')
                ->references('id') // organization_id
                ->on('organizations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
};
