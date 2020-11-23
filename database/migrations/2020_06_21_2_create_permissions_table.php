<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-authentication')->create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id');
            $table->foreignId('system_id')->comment('Para que el permiso pertenezca a un sistema');
            $table->foreignId('institution_id')->constrained('ignug.institutions');
            $table->json('actions')->comment('[CREATE, UPDATE, DELETE, SELECT], etc');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-authentication')->dropIfExists('permissions');
    }
}
