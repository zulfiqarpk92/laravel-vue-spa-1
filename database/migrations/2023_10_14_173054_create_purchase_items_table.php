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
    Schema::create('purchase_items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('purchase_id')->default(0)->index();
      $table->unsignedBigInteger('item_id')->default(0)->index();
      $table->string('description')->default('');
      $table->integer('quantity')->default(0);
      $table->float('purchase_price')->default(0);
      $table->float('discount')->default(0);
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
    Schema::dropIfExists('purchase_items');
  }
};
