<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferProfessionalTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-job-board')->create('offer_professional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id');
            $table->foreignId('offer_id');
            $table->foreignId('status_id')->nullable()->constrained('ignug.catalogues');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-job-board')->dropIfExists('offer_professional');
    }
}
