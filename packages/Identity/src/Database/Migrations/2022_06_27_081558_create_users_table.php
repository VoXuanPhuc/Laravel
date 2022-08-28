<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $tableName = 'users';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if( !Schema::hasTable($this->tableName)) {

            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->uuid('uid')->default(DB::raw('(UUID())'))->unique();
                $table->string('first_name')->nullable(false);
                $table->string('last_name')->nullable(false);
                $table->string('gender')->nullable(true);
                $table->date('dob')->nullable(true);
                $table->string('email')->unique()->nullable(false);
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('phone')->nullable(true);
                $table->integer('is_verified')->default(0);
                $table->integer('is_suspended')->default(0);
                $table->rememberToken();

                $table->softDeletes();

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
        Schema::dropIfExists('users');
    }
};
