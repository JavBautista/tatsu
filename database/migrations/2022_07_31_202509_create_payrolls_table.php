<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->boolean('active')->default(1);
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->decimal('base',8,2)->nullable();
            $table->decimal('discount',8,2)->nullable();
            $table->decimal('bonus',8,2)->nullable();
            $table->decimal('total',8,2)->nullable();
            $table->text('observations')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}
