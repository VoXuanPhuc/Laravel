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
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->uuid( 'uid')->default(\Illuminate\Support\Facades\DB::raw('(UUID())'))
                ->unique()
                ->index('idx_notification_uid')
            ;

            $table->string('type');
            $table->morphs('notifiable');
            $table->string('title');
            $table->text('data');
            $table->boolean('pinned')->nullable();
            $table->timestamp('read_at')->nullable();

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
