<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\SaleResource;
use App\Models\User;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
  /**
   * Get customer ledger including all sales and payments
   *
   * @param int $id
   * @param Illuminate\Http\Request $request
   *
   */
  public function ledger($id, Request $request)
  {
    $include = ['sales', 'payments'];
    $customer = User::when($include, fn($query) => $query->with($include))->find($id);
    // dd($customer);
    // dd($customer->sales[0]->items[0]->item);
    $ledger = collect([]);
    $ledger->push(['type' => 'opening', 'amount' => 0]);
    $ledger->push(...$customer->sales->map(fn($sale) => [...(new SaleResource($sale))->toArray(null), 'type' => 'sale'])->toArray());
    $ledger->push(...$customer->payments->map(fn($payment) => [...(new PaymentResource($payment))->toArray(null), 'type' => 'payment'])->toArray());
    $ledger = $ledger->map(function($row){
      $new_row = [...$row];
      $new_row['ref_date'] = null;
      if(isset($row['sale_time'])){
        $new_row['ref_date'] = $row['sale_time'];
      }
      elseif(isset($row['payment_time'])){
        $new_row['ref_date'] = explode(' ', $row['payment_time'])[0];
      }
      $new_row['tendered_amount'] = 0;
      if(isset($row['total_amount'])){
        $new_row['tendered_amount'] = $row['total_amount'];
      }
      elseif(isset($row['amount'])){
        $new_row['tendered_amount'] = $row['amount'];
      }
      return $new_row;
    });
    $ledger = $ledger->sortBy('ref_date');
    // dd($ledger->toArray());
    return ['customerInfo' => $customer, 'ledger' => $ledger->values()->all()];
    dd($ledger->sortBy('created_at')->toArray());
    return SaleResource::collection($customer->sales);
    return new CustomerResource($customer);
  }
}
