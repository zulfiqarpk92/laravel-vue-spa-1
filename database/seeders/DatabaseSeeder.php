<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();
    // \App\Models\Item::factory(100)->create();

    $this->cleanUpDatabase();

    $this->call([
      ItemSeeder::class,
      CustomerSeeder::class,
      SupplierSeeder::class,
    ]);

  }

  private function cleanUpDatabase()
  {
    Item::truncate();
    Sale::truncate();
    SaleItem::truncate();
    Payment::truncate();
    Purchase::truncate();
    PurchaseItem::truncate();
    User::where('id', '>', '1')->forceDelete();
  }
}
