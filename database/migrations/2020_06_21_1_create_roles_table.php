<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-authentication')->create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('No debe ser modificado una vez que se lo crea');
            $table->text('name');
            $table->foreignId('system_id')->comment('Para que el rol pertenezca a un sistema');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-authentication')->dropIfExists('roles');
    }
}
