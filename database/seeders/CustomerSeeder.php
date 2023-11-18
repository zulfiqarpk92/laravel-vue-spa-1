<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
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
    $this->importCustomers($content['customers']);
  }

  private function importCustomers($customers)
  {
    foreach($customers as $customer)
    {
      $this->importCustomer($customer);

      foreach($customer['sales'] as $sale)
      {
        // import only completed sales
        if($sale['sale_status'] == '0')
        {
          $this->importCustomerSale($sale);
        }
      }
    }
  }

  private function importCustomer($customer)
  {
    User::create([
      'id'                => $customer['person_id'],
      'name'              => trim($customer['first_name'] . ' ' . $customer['last_name']),
      'email'             => $customer['email'] ?: ('email'.$customer['person_id'].'@dummy.com'),
      'phone'             => $customer['phone_number'] ?: '',
      'address'           => trim(($customer['address_1'] ?: '') . ' ' . ($customer['address_2'] ?: '')),
      'city'              => $customer['city'] ?: '',
      'province'          => $customer['state'] ?: '',
      'role'              => 'user',
      'is_customer'       => '1',
      'is_supplier'       => '0',
      'comments'          => $customer['comments'] ?: '',
      'email_verified_at' => now(),
      'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token'    => '',
      'created_at'        => $customer['date'] ?? date('Y-m-d'),
    ]);
  }

  private function importCustomerSale($sale)
  {
    $saleRecord = Sale::create([
      'id'            => $sale['sale_id'],
      'customer_id'   => $sale['customer_id'],
      'sale_time'     => $sale['sale_time'],
      'total_amount'  => $sale['sale_total'],
      'comments'      => $sale['comment'],
      'created_by'    => $sale['employee_id'],
    ]);

    foreach($sale['items'] as $sale_item)
    {
      $saleRecord->items()->create([
        'item_id'       => $sale_item['item_id'],
        'quantity'      => $sale_item['quantity_purchased'],
        'cost_price'    => $sale_item['item_cost_price'],
        'sale_price'    => $sale_item['item_unit_price'],
        'created_by'    => $sale['employee_id'],
      ]);
    }

    foreach($sale['payments'] as $sale_payment)
    {
      if($sale_payment['payment_type'] == 'Due') continue;
      $saleRecord->payments()->create([
        'user_id'       => $sale['customer_id'],
        'payment_time'  => $sale_payment['payment_time'],
        'description'   => '',
        'amount'        => $sale_payment['payment_amount'],
        'created_by'    => $sale_payment['employee_id'],
      ]);
    }
    $saleRecord->recalculate();
  }
}
