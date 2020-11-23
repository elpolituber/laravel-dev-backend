<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebCataloguesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-web')->create('catalogues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->references('id')->on('catalogues');
            $table->text('code');
            $table->text('name');
            $table->text('type');
            $table->text('icon')->nullable();
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-web')->dropIfExists('catalogues');
    }
}
