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
      'id'          => $this->id,
      'user_id'     => $this->user_id,
      'description' => $this->description,
      'amount'      => $this->amount,
      'created_at'  => $this->created_at->format('d-m-Y H:i'),
    ];
  }
}
