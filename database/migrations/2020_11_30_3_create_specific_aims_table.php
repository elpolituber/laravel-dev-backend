<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecificAimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-community')->create('specific_aims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->foreignId('project_id')->connstrained('community.projects');
            $table->text('indicator');
            $table->json('verifications')->nullable();
            $table->text('description')->nullable();//linea base
            $table->foreignId('type_id')->connstrained('ignug.catalogues');//crear tipo de catologos
            $table->foreignId('parent_code_id')->nullable()->connstrained('specific_aims');//tabla recusiva
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
        Schema::dropIfExists('specific_aims');
    }
}
