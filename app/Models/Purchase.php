<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends BaseModel
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
    'purchase_time',
    'supplier_id',
    'comments',
  ];

  protected $casts = [
    'purchase_time' => 'datetime',
  ];

  public function recalculate()
  {
    $this->total_amount = $this->items->sum(fn($item) => round($item->quantity * $item->purchase_price, 2));
    $this->balance = round($this->total_amount - $this->discount_amount - $this->paidAmount(), 2);
    $this->save();
  }

  public function scopePaidAmount()
  {
    return round(0, 2);
  }

  public function supplier()
  {
    return $this->belongsTo(User::class);
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function items()
  {
    return $this->hasMany(PurchaseItem::class);
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
    if($request->input('supplier'))
    {
      $query->where('supplier_id', $request->input('supplier'));
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
