<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
      'customerId'    => ['required', 'integer', 'gt:0'],
      'invoiceItems'  => ['required', 'array', 'min:1'],
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
      'customerId.required' => 'The supplier field is required.',
      'customerId.gt' => 'The supplier field is required.',
      'invoiceItems.required' => 'Please add one or more items.',
    ];
  }
}
