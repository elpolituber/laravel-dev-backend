<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-web')->create('texts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id');
            $table->foreignId('type_id')->constrained('ignug.catalogues');
            $table->text('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('description');
            $table->foreignId('status_id')->constrained('ignug.catalogues');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-web')->dropIfExists('texts');
    }
}
