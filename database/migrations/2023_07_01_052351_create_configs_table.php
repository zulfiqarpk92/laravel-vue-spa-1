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
    Schema::create('configs', function (Blueprint $table) {
      $table->id();
      $table->string('config_key', 50)->unique();
      $table->string('config_value', 50)->default('');
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
    Schema::dropIfExists('configs');
  }
};
