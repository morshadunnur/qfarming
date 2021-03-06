<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('company_id')->unsigned()->index();
            $table->string('purchase_no')->unique();
            $table->date('purchase_date');
            $table->date('due_date')->nullable();
            $table->string('payment_type');
            $table->string('bank')->nullable();
            $table->decimal('sub_total',15,2);
            $table->decimal('discount',15,2);
            $table->decimal('grand_total',19,2);
            $table->tinyInteger('status')->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
