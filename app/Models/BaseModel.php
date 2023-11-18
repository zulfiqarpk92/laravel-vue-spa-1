<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
  use HasFactory;

  /**
   * The "booted" method of the model.
   *
   * @return void
   */
  protected static function booted()
  {
    static::creating(function ($record) {
      $record->created_by = intval(auth()->id());
    });
    static::updating(function ($record) {
      $record->updated_by = intval(auth()->id());
    });
    static::deleted(function ($record) {
      $record->deleted_by = intval(auth()->id());
      $record->save();
    });
  }
}
