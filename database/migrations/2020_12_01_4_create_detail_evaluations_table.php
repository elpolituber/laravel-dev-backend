<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-teacher-eval')->create('detail_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->morphs('detail_evaluationable');
            $table->foreignId('evaluation_id');
            $table->double('result',5,2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-teacher-eval')->dropIfExists('detail_evaluations');
    }
}
