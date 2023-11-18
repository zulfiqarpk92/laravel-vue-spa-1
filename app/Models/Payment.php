<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends BaseModel
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'user_id',
    'sale_id',
    'payment_time',
    'description',
    'payment_mode',
    'reference',
    'amount',
  ];

  protected $casts = [
    'payment_time' => 'datetime',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function sale()
  {
    return $this->belongsTo(Sale::class);
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function scopeListWithFilters(Builder $query, $request)
  {
    if($request->input('q'))
    {
      $q =  '%' . $request->input('q') . '%';
      $query->where('description', 'LIKE', $q);
    }
    if($request->input('saleId'))
    {
      $query->where('sale_id', $request->input('saleId'));
    }
    if($request->input('userId'))
    {
      $query->where('user_id', $request->input('userId'));
    }
    $orderBy = $request->get('orderBy');
    $orderDesc = $request->boolean('orderDesc') ? 'desc' : 'asc';
    if (!in_array($orderBy, ['id', 'description', 'amount', 'created_at'])) {
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
