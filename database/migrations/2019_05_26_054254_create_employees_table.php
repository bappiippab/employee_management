<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("first_name")->required();
            $table->string("last_name")->required();
            $table->bigInteger("company")->unsigned();;
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->timestamps();
        });

        Schema::table('employees', function($table) {
            $table->foreign('company')->references('id')->on('companies');
        });
    }

    // First name (required), last name (required), Company (foreign key to Companies), email, phone

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
