<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
  use HasFactory;

  public $timestamps = false;

  protected $fillable = [
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
