<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleItem extends BaseModel
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'sale_id',
    'item_id',
    'description',
    'quantity',
    'cost_price',
    'sale_price',
  ];

  public function sale()
  {
    return $this->belongsTo(Sale::class);
  }

  public function item()
  {
    return $this->belongsTo(Item::class);
  }
}
