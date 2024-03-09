<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_cases', function (Blueprint $table) {
            $table->id();
            $table->text('test_domain');
            $table->string('module_name');
            $table->text('test_description');
            $table->boolean('test_case_type');
            $table->text('test_step');
            $table->text('test_data');
            $table->text('expected_result');
            $table->text('actual_result')->nullable();
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
        Schema::dropIfExists('test_cases');
    }
}
