<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $path = storage_path('export.json');
    $content = json_decode(file_get_contents($path), TRUE);
    $this->importItems($content['items']);
  }

  private function importItems($items)
  {
    foreach($items as $item)
    {
      Item::create([
        'id'          => $item['item_id'],
        'item_name'   => trim($item['name']),
        'supplier_id' => $item['supplier_id'] ?: 0,
        // 'category_id' => Collect(['Brush', 'Powder', 'Food'])->random(),
        'item_type'   => $item['item_type'],
        'cost_price'  => $item['cost_price'],
        'sale_price'  => $item['unit_price'],
        'bulk_price'  => $item['wsale_price'],
      ]);
    }
  }
}
