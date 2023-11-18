<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
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
    $this->importSuppliers($content['suppliers']);
  }

  private function importSuppliers($suppliers)
  {
    foreach($suppliers as $supplier)
    {
      $this->importSupplier($supplier);

      foreach($supplier['receivings'] as $recv)
      {
        $this->importSupplierReceiving($recv, $supplier);
      }
    }
  }

  private function importSupplier($supplier)
  {
    $user = User::where('id', $supplier['person_id']);
    if($user->exists())
    {
      $user->update([
        'is_supplier'       => '1',
        'company_name'      => $supplier['company_name'] ?: '',
        'comments'          => trim(($supplier['agency_name'] ?: '') . ' ' . ($supplier['comments'] ?: '')),
      ]);
    }
    else{
      User::create([
        'id'                => $supplier['person_id'],
        'name'              => trim($supplier['first_name'] . ' ' . $supplier['last_name']),
        'company_name'      => $supplier['company_name'] ?: '',
        'email'             => $supplier['email'] ?: ('email'.$supplier['person_id'].'@dummy.com'),
        'phone'             => $supplier['phone_number'] ?: '',
        'address'           => trim(($supplier['address_1'] ?: '') . ' ' . ($supplier['address_2'] ?: '')),
        'city'              => $supplier['city'] ?: '',
        'province'          => $supplier['state'] ?: '',
        'role'              => 'user',
        'is_customer'       => '0',
        'is_supplier'       => '1',
        'comments'          => $supplier['comments'] ?: '',
        'email_verified_at' => now(),
        'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token'    => '',
        'created_at'        => $supplier['date'] ?? date('Y-m-d'),
      ]);
    }
  }

  private function importSupplierReceiving($recv, $supplier)
  {
    $purchase = Purchase::create([
      'id'            => $recv['receiving_id'],
      'supplier_id'   => $supplier['person_id'],
      'purchase_time' => $recv['receiving_time'],
      'comments'      => ($recv['reference'] ? 'Ref: ' . $recv['reference'] : '') . ($recv['comment'] ? "\n\nComments: " . $recv['comment'] : ''),
      'created_by'    => $recv['employee_id'],
    ]);

    foreach($recv['items'] as $recv_item)
    {
      $qty = abs($recv_item['quantity_purchased'] * ($recv_item['receiving_quantity'] ?: 1));
      $discount = 0;
      if($recv_item['discount_type'] == 1)
      {
        $discount = $recv_item['discount'];
      }
      else{
        $total_amount = $recv_item['item_unit_price'] * $qty;
        $discount = $total_amount * $recv_item['discount'] / 100;
      }
      $purchase->items()->create([
        'purchase_id'     => $recv_item['receiving_id'],
        'item_id'         => $recv_item['item_id'],
        'description'     => $recv_item['description'] ?: '',
        'quantity'        => $qty,
        'purchase_price'  => $recv_item['item_unit_price'],
        'discount'        => $discount,
        'created_by'      => $recv['employee_id'],
      ]);
    }
    $purchase->recalculate();

    // foreach($recv['payments'] as $recv_payment)
    // {
    //   if($recv_payment['payment_type'] == 'Due') continue;
    //   Payment::create([
    //     'user_id'       => $recv['customer_id'],
    //     'sale_id'       => $recv_payment['sale_id'],
    //     'employee_id'   => $recv_payment['employee_id'],
    //     'payment_time'  => $recv_payment['payment_time'],
    //     'description'   => '',
    //     'amount'        => $recv_payment['payment_amount'],
    //   ]);
    // }
  }
}
