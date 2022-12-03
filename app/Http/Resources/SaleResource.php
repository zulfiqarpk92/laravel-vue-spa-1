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
        'item_id'   => $item->item_id,
        'name'      => $item->item?->name,
        'price'     => round($item->sale_price, 2),
        'quantity'  => round($item->quantity, 2),
      ];
    });
    $invoice_total = $this->items->sum(function ($item) {
        return $item->sale_price * $item->quantity;
    });

    $customer = null;
    if ($this->customer) {
      $customer = [
        'name'          => $this->customer->name,
        'company_name'  => $this->customer->company_name,
      ];
    }

    return [
      'id'          => $this->id,
      'sale_time'   => $this->sale_time->format('Y-m-d'),
      'customer'    => $customer,
      'items'       => $items,
      'total_qty'   => $this->items->sum(fn($item) => $item->quantity),
      'total_amount'=> $invoice_total,
      'created_at'  => $this->created_at->format('d-m-Y H:i'),
      // 'payment'   => $payment_total,
      // 'balance'   => $invoice_total - $payment_total,
      // 'comment'   => $this->comment,
    ];
  }
}
