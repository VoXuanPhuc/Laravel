<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected const TABLE = 'documents';
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
                $table->uuid( 'uid')->default(DB::raw('(UUID())'))->unique();
                $table->string('name');
                $table->string('path');
                $table->decimal('size', 12)->nullable();
                $table->string('mime_type', 50)->nullable();
                $table->string('type', 127)->nullable();
                $table->unsignedInteger('uploaded_by')->nullable();

                $table->nullableMorphs('model');

                $table->foreignId('organization_id');
                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
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
        if(Schema::hasTable(self::TABLE)) {
            Schema::dropIfExists(self::TABLE);
        }
    }
};
