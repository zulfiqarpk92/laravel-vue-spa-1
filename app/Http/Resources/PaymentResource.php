<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
      'id'            => $this->id,
      'userId'        => $this->user_id,
      'user'          => $this->user->name,
      'saleId'        => $this->sale_id,
      'linkedTo'      => $this->sale?->id,
      'paymentTime'   => $this->payment_time->format('Y-m-d H:i'),
      'description'   => $this->description,
      'paymentMode'   => $this->payment_mode,
      'reference'     => $this->reference,
      'amount'        => $this->amount,
      'createdAt'     => $this->created_at->format('Y-m-d H:i'),
      'createdBy'     => $this->creator?->name,
    ];
  }
}
