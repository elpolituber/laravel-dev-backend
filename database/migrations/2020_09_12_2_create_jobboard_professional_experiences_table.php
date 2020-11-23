<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobboardProfessionalExperiencesTable extends Migration
{

    public function up()
    {
        Schema::connection('pgsql-job-board')->create('professional_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id');
            $table->string('employer');
            $table->string('position', 100);
            $table->string('job_description', 1000);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('reason_leave', 1000);
            $table->boolean('current_work')->default(false);
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::connection('pgsql-job-board')->dropIfExists('professional_experiences');
    }
}
