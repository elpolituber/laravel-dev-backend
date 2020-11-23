<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfessionalTable extends Migration
{

    public function up()
    {
        Schema::connection('pgsql-job-board')->create('company_professional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('professional_id');
            $table->foreignId('status_id')->nullable()->constrained('ignug.catalogues');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::connection('pgsql-job-board')->dropIfExists('company_professional');
    }
}
