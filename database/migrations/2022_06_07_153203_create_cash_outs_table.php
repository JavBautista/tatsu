<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_outs', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->decimal('expenses',8,2)->nullable();
            $table->decimal('sales',8,2)->nullable();
            $table->decimal('card_sales',8,2)->nullable();
            $table->decimal('card_tips',8,2)->nullable();
            $table->decimal('total',8,2)->nullable();
            $table->decimal('cash',8,2)->nullable();
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
        Schema::dropIfExists('cash_outs');
    }
}
