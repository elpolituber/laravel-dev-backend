<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{

    public function up()
    {
        Schema::connection('pgsql-job-board')->create('languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id');
            $table->foreignId('written_level_id')->constrained('ignug.catalogues');
            $table->foreignId('spoken_level_id')->constrained('ignug.catalogues');
            $table->foreignId('reading_level_id')->constrained('ignug.catalogues');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::connection('pgsql-job-board')->dropIfExists('languages');
    }
}
