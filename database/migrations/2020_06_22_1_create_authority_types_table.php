<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorityTypesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-ignug')->create('authority_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->comment('Un tipo puede tener tipos hijos')->constrained('authority_types');
            $table->text('name')->unique();
            $table->foreignId('state_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-ignug')->dropIfExists('authority_types');
    }
}
