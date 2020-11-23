<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-authentication')->create('password_resets', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('token')->index();
            $table->boolean('is_valid')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-authentication')->dropIfExists('password_resets');
    }
}
