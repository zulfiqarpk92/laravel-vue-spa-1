<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
  use HasFactory;

  protected $fillable = [
    'payment_type',
    'payment_amount',
    'cash_refund',
    'payment_time',
    'reference_code',
  ];
}
