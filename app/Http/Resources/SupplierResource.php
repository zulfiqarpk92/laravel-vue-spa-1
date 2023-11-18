<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id'          => $this->id,
      'name'        => $this->name,
      'email'       => $this->email,
      'phone'       => $this->phone,
      'address'     => $this->address,
      'city'        => $this->city,
      'province'    => $this->province,
      'comments'    => $this->comments,
      'role'        => $this->readableRole(),
      'created_at'  => $this->created_at ? $this->created_at->format('Y-m-d H:i') : '',
      // 'invoices'    => SaleResource::collection($this->whenLoaded('sales')),
      // 'payments'    => PaymentResource::collection($this->whenLoaded('payments')),
    ];
  }
}
