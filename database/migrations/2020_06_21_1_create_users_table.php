<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-authentication')->create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ethnic_origin_id')->nullable()->constrained('ignug.catalogues');
            $table->foreignId('address_id')->nullable()->constrained('ignug.address');
            $table->foreignId('identification_type_id')->nullable()->constrained('ignug.catalogues');
            $table->foreignId('sex_id')->nullable()->constrained('ignug.catalogues');
            $table->foreignId('gender_id')->nullable()->constrained('ignug.catalogues');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->foreignId('status_id');
            $table->string('avatar')->nullable()->unique();
            $table->string('username', 50)->unique();
            $table->string('identification', 20);
            $table->string('first_name', 1000)->nullable();
            $table->string('second_name', 100)->nullable();
            $table->string('first_lastname', 100)->nullable();
            $table->string('second_lastname', 100)->nullable();
            $table->string('personal_email', 100)->nullable()->unique();
            $table->date('birthdate')->nullable();
            $table->foreignId('blood_type_id')->nullable()->constrained('ignug.catalogues');
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 200);
            $table->boolean('change_password')->default(false);
            $table->integer('attempts')->default(\App\Models\Authentication\User::ATTEMPTS);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-authentication')->dropIfExists('users');
    }
}
