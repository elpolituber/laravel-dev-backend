<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-web')->create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->nullable();
            $table->foreignId('template_id')->constrained('ignug.catalogues');
            $table->foreignId('section_id')->nullable();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-web')->dropIfExists('pages');
    }
}
