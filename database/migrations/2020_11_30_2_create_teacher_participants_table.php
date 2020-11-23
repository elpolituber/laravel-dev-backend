<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-community')->create('teacher_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->foreignId('teacher_id')->connstrained('ignug.users');
            $table->foreignId('project_id')->connstrained('community.projects');
            $table->integer('workHours');//horas de trabajo
            $table->string('funtion_id',100)->constrained('ignug.catalogues');//rol asignado catalogo 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_participants');
    }
}
