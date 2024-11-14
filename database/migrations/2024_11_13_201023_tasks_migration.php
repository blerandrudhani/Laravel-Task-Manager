<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->nullable(false);
            $table->string('Description')->nullable(false);
            $table->boolean('status')->default(false);
            $table->integer('priority');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('edited_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('tasks');
    }
};
