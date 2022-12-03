<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
  public function index(Request $request)
  {
    $customers = User::customers($request);
    return CustomerResource::collection($customers);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function search(Request $request)
  {
    $items = User::search($request->input('q'), 'customer');
    if ($items->IsEmpty()) {
      $items = [[
        'id'    => 0,
        'name'  => $request->input('q')
      ]];
    }

    return response()->json($items);
  }

  public function store(CustomerRequest $request)
  {
    $customer = new User($request->validated());
    $customer->is_customer = true;
    $customer->save();
    return response()->json([
      'status'  => 'success',
      'message' => 'Customer record added successfully.',
      'data'    => ['customer_id' => $customer->id]
    ]);
  }

  public function show($id, Request $request)
  {
    $include = $request->input('include');
    $customer = User::when($include, fn($query) => $query->with($include))->find($id);
    return new CustomerResource($customer);
  }

  public function update($id, CustomerRequest $request)
  {
    $customer = User::findOrFail($id);
    $customer->update($request->all());
    return response()->json([
      'status'  => 'success',
      'message' => 'Customer record updated successfully.',
      'data'    => ['customer_id' => $customer->id]
    ]);
  }

  public function destroy($id)
  {
    $customer = User::find($id);
    $customer->delete();
    return response()->json([
      'message' => "Customer $customer->name deleted successfully."
    ]);
  }
}
