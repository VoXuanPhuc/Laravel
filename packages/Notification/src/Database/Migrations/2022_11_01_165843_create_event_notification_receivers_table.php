<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected const TABLE = 'event_notification_receivers';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable(self::TABLE)){
            Schema::create('event_notification_receivers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('receivable');

                $table->foreignId('event_notification_id');

                $table->foreign('event_notification_id')
                    ->references('id')
                    ->on('event_notifications')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

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
