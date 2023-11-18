<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
        'id'            => $item->id,
        'itemId'        => $item->item_id,
        'itemName'      => $item->item?->item_name,
        'purchasePrice' => round($item->purchase_price, 2),
        'quantity'      => round($item->quantity, 2),
        'total'         => round($item->quantity * $item->purchase_price, 2),
      ];
    });

    $supplier = null;
    if ($this->supplier) {
      $supplier = [
        'id'            => $this->supplier->id,
        'name'          => $this->supplier->name,
        'companyName'   => $this->supplier->company_name,
        'address'       => $this->supplier->address,
        'phone'         => $this->supplier->phone,
        'email'         => $this->supplier->email,
      ];
    }

    return [
      'id'              => $this->id,
      'purchaseTime'    => $this->purchase_time->format('Y-m-d'),
      'dueDate'         => $this->purchase_time->addDays(14)->format('Y-m-d'),
      'supplier'        => $supplier,
      'items'           => $items,
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
