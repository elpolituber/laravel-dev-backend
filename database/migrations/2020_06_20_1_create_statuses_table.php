<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-authentication')->create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('1. ACTIVE, 2. INACTIVE, 3. LOCKED');
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-authentication')->dropIfExists('statuses');
    }
}
