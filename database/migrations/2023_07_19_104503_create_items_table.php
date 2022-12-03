<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
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
      $table->unsignedInteger('category_id')->index();
      $table->unsignedInteger('supplier_id')->index();
      $table->tinyInteger('item_type');
      $table->string('barcode', 20)->nullable();
      $table->float('cost_price')->default(0);
      $table->float('sale_price')->default(0);
      $table->float('bulk_price')->default(0);
      $table->integer('available_quantity')->default(0);
      $table->text('description')->default('');
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
}
