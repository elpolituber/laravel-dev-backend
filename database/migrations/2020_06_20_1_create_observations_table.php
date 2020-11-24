<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-ignug')->create('observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('state_id');
            $table->morphs('observationable');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->text('description');
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
        Schema::connection('pgsql-ignug')->dropIfExists('observations');
    }
}
