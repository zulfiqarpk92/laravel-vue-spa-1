<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends BaseModel
{
  use HasFactory, SoftDeletes;

  const SALE_TYPE_POS = 0;
  const SALE_TYPE_INVOICE = 1;
  const SALE_TYPE_WORK_ORDER = 2;
  const SALE_TYPE_QUOTE = 3;
  const SALE_TYPE_RETURN = 4;

  const SALE_STATUS_COMPLETED = 0;
  const SALE_STATUS_SUSPENDED = 1;
  const SALE_STATUS_CANCELED = 2;

  protected $fillable = [
    'sale_time',
    'customer_id',
    'comments',
    'sale_status',
    'sale_type',
  ];

  protected $casts = [
    'sale_time' => 'datetime',
  ];

  public function recalculate()
  {
    $this->total_amount = $this->items->sum(fn($item) => round($item->quantity * $item->sale_price, 2));
    $this->balance = round($this->total_amount - $this->discount_amount - $this->paidAmount(), 2);
    $this->save();
  }

  public function scopePaidAmount()
  {
    return round($this->payments->sum(fn($payment) => $payment->amount), 2);
  }

  public function customer()
  {
    return $this->belongsTo(User::class);
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function items()
  {
    return $this->hasMany(SaleItem::class);
  }

  public function payments()
  {
    return $this->hasMany(Payment::class);
  }

  public function scopeListWithFilters(Builder $query, $request)
  {
    if($request->input('q'))
    {
      $q =  '%' . $request->input('q') . '%';
      $query->whereHas('items.item', function($query) use($q) {
        $query->where('item_name', 'LIKE', $q);
      });
    }
    if($request->input('customer'))
    {
      $query->where('customer_id', $request->input('customer'));
    }
    $orderBy = $request->get('orderBy');
    $orderDesc = $request->boolean('orderDesc') ? 'desc' : 'asc';
    if (!in_array($orderBy, ['id', 'name', 'email', 'phone', 'created_at'])) {
      $orderBy = 'id';
      $orderDesc = 'desc';
    }

    $query->orderBy($orderBy, $orderDesc);
    if($request->get('per_page') == -1)
    {
      return $query->get();
    }
    else
    {
      return $query->paginate($request->get('per_page'));
    }
  }

}
