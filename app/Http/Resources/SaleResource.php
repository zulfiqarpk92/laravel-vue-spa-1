<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $items = $this->items->map(function ($item) {
      return [
        'id'        => $item->id,
        'itemId'    => $item->item_id,
        'itemName'  => $item->item?->item_name,
        'costPrice' => round($item->cost_price, 2),
        'salePrice' => round($item->sale_price, 2),
        'quantity'  => round($item->quantity, 2),
        'total'     => round($item->quantity * $item->sale_price, 2),
      ];
    });
    $payments = $this->payments->map(function ($payment) {
      return [
        'id'          => $payment->id,
        'paymentTime' => $payment->payment_time->format('Y-m-d'),
        'paymentMode' => $payment->payment_mode,
        'description' => $payment->description ?: '',
        'reference'   => $payment->reference ?: '',
        'amount'      => round($payment->amount, 2),
      ];
    });

    $customer = null;
    if ($this->customer) {
      $customer = [
        'id'            => $this->customer->id,
        'name'          => $this->customer->name,
        'companyName'   => $this->customer->company_name,
        'address'       => $this->customer->address,
        'phone'         => $this->customer->phone,
        'email'         => $this->customer->email,
      ];
    }

    return [
      'id'              => $this->id,
      'saleTime'        => $this->sale_time->format('Y-m-d'),
      'dueDate'         => $this->sale_time->addDays(14)->format('Y-m-d'),
      'customerId'      => $this->customer_id ?: 0,
      'customer'        => $customer,
      'items'           => $items,
      'payments'        => $payments,
      'totalQty'        => $this->items->sum(fn($item) => $item->quantity),
      'totalAmount'     => $this->total_amount,
      'discountAmount'  => $this->discount_amount,
      'amountPaid'      => $this->paidAmount(),
      'balance'         => $this->balance,
      'createdAt'       => $this->created_at->format('Y-m-d H:i'),
      'createdBy'       => $this->creator->name,
      'comments'        => $this->comments ?? '',
    ];
  }
}
