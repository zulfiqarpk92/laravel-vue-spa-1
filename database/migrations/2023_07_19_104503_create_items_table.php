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
    Schema::create('items', function (Blueprint $table) {
      $table->id();
      $table->string('item_name')->index();
      $table->unsignedInteger('category_id')->default(0)->index();
      $table->unsignedInteger('supplier_id')->default(0)->index();
      $table->tinyInteger('item_type')->default(0);
      $table->string('barcode', 20)->nullable();
      $table->float('cost_price')->default(0);
      $table->float('sale_price')->default(0);
      $table->float('bulk_price')->default(0);
      $table->integer('available_quantity')->default(0);
      $table->text('description')->default('');
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
    Schema::dropIfExists('items');
  }
};
