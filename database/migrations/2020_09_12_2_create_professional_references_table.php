<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalReferencesTable extends Migration
{

    public function up()
    {
        Schema::connection('pgsql-job-board')->create('professional_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id');
            $table->string('institution');
            $table->string('position');
            $table->string('contact');
            $table->string('phone');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::connection('pgsql-job-board')->dropIfExists('professional_references');
    }
}
