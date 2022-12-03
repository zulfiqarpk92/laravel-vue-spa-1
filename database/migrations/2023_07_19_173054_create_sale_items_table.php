<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleItemsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('sale_items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('sale_id')->default(0);
      $table->unsignedBigInteger('item_id')->default(0);
      $table->integer('quantity')->default(0);
      $table->float('cost_price')->default(0);
      $table->float('sale_price')->default(0);
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
    Schema::dropIfExists('sale_items');
  }
}
