0<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('path', 255);
            $table->string('entity', 150);
            $table->integer('imageable_id')->nullable()->unsigned();
            $table->string('imageable_type', 80)->nullable();
            $table->tinyInteger('is_video')->default(0)->unsigned();
            $table->boolean('pending')->default(1);
            $table->timestamps();

            //indices
            $table->index(['imageable_id', 'imageable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
