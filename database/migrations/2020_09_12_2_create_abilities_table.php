<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbilitiesTable extends Migration
{

    public function up()
    {
        Schema::connection('pgsql-job-board')->create('abilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id');
            $table->foreignId('category_id')->constrained('ignug.catalogues');
            $table->string('description');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::connection('pgsql-job-board')->dropIfExists('abilities');
    }
}
