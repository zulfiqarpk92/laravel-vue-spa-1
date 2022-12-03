<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('sales', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('customer_id')->default(0);
      $table->unsignedBigInteger('employee_id')->default(0);
      $table->timestamp('sale_time')->nullable();
      $table->double('total_amount')->default(0);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('sales');
  }
}
