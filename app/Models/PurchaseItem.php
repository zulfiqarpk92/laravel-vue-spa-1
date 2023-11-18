<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseItem extends BaseModel
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'purchase_id',
    'item_id',
    'description',
    'quantity',
    'purchase_price',
  ];

  public function purchase()
  {
    return $this->belongsTo(Purchase::class);
  }

  public function item()
  {
    return $this->belongsTo(Item::class);
  }
}
