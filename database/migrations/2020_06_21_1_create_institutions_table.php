<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-ignug')->create('institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id');
            $table->string('code')->nullable();
            $table->string('acronym', 10)->nullable();
            $table->string('denomination')->nullable();
            $table->string('name')->unique();
            $table->string('short_name')->unique();
            $table->text('slogan')->nullable();
            $table->string('logo')->nullable();
            $table->string('web')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-ignug')->dropIfExists('institutions');
    }
}
