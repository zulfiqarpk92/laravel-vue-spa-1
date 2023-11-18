<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
      $table->unsignedBigInteger('customer_id')->default(0)->index();
      $table->timestamp('sale_time')->nullable();
      $table->double('total_amount')->default(0);
      $table->double('discount_amount')->default(0);
      $table->double('balance')->default(0);
      $table->text('comments');
      $table->unsignedBigInteger('created_by')->default(0)->index();
      $table->unsignedBigInteger('updated_by')->default(0)->index();
      $table->unsignedBigInteger('deleted_by')->default(0)->index();
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
};
