<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-ignug')->create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('uri');
            $table->string('extension');
            $table->string('type');
            $table->foreignId('state_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-ignug')->dropIfExists('images');
    }
}
