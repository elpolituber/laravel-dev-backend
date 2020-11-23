<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{

    public function up()
    {
        Schema::connection('pgsql-job-board')->create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories');
            $table->text('code', 100);
            $table->text('name', 500);
            $table->foreignId('type_id')->constrained('ignug.catalogues');
            $table->text('icon', 200)->nullable();
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-job-board')->dropIfExists('categories');
    }
}
