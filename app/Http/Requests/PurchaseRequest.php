<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
      'supplierId'      => ['required', 'integer', 'gt:0'],
      'purchaseItems'   => ['required', 'array', 'min:1'],
    ];
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, string>
   */
  public function messages()
  {
    return [
      'supplierId.required' => 'The supplier field is required.',
      'supplierId.gt' => 'The supplier field is required.',
      'purchaseItems.required' => 'Please add one or more items.',
    ];
  }
}
