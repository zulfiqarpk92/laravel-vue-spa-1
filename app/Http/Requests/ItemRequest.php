<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
      */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'item_name'     => 'required',
      'supplier_id'   => 'required',
      'category_id'   => 'required',
      'item_type'     => 'required',
      'cost_price'    => 'required|numeric',
      'sale_price'    => 'required|numeric',
      'bulk_price'    => 'numeric',
    ];
  }
}
