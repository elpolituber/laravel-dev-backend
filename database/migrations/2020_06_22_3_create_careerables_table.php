<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerablesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-ignug')->create('careerables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_id');
            $table->morphs('careerable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-ignug')->dropIfExists('careerables');
    }
}
