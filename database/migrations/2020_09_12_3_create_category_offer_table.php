<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryOfferTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-job-board')->create('category_offer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->foreignId('offer_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-job-board')->dropIfExists('category_offer');
    }
}
