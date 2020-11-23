<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogueablesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-ignug')->create('catalogueables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalogue_id');
            $table->morphs('catalogueable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-ignug')->dropIfExists('catalogueables');
    }
}
