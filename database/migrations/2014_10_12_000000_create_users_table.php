<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('company_name')->default('');
      $table->string('email')->unique();
      $table->string('phone', 255)->default('');
      $table->string('address', 255)->default('');
      $table->string('city', 100)->default('');
      $table->string('province', 100)->default('');
      $table->string('comments', 512)->nullable();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password')->nullable();
      $table->string('role', 10);
      $table->boolean('is_customer')->default(0);
      $table->boolean('is_supplier')->default(0);
      $table->rememberToken();
      $table->unsignedBigInteger('created_by')->default(0)->index();
      $table->unsignedBigInteger('updated_by')->default(0)->index();
      $table->unsignedBigInteger('deleted_by')->default(0)->index();
      $table->timestamps();
      $table->softDeletes();
    });

    DB::table('users')->insert([
      'name'      => 'Webmaster',
      'email'     => 'admin@example.com',
      'password'  => Hash::make('123456'),
      'role'      => 'admin',
      'created_at'=> date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
