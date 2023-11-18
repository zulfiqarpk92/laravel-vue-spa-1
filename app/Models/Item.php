<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends BaseModel
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'item_name',
    'category',
    'item_type',
    'barcode',
    'company_name',
    'cost_price',
    'sale_price',
    'bulk_price',
    'available_quantity',
    'description'
  ];

  public function scopeListWithFilters(Builder $query, $request)
  {
    if($request->input('q'))
    {
      $q =  '%' . $request->input('q') . '%';
      $query->where('item_name', 'LIKE', $q)
        ->orWhere('item_type', 'LIKE', $q);
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
    return $query;
  }

}
