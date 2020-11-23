<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharitableInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-community')->create('charitable_institutions', function (Blueprint $table) {
            //beneficiary
            $table->id();
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->string('ruc',15);
            $table->text('name',300);
            $table->foreignId('location_id')->nullable()->constrained('ignug.catalogues');//fk propia
            $table->json('indirect_beneficiaries')->nullable();
            $table->string('legal_representative_name',100);
            $table->string('legal_representative_lastname',100);
            $table->string('legal_representative_identification',100);
            $table->string('project_post_charge',100);
            $table->json('direct_beneficiaries')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charitable_institutions');
    }
}
