<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebSettingsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-web')->create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description');
            $table->text('value');
            $table->foreignId('type_id')->constrained('ignug.catalogues');
            $table->string('image');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-web')->dropIfExists('settings');
    }
}
