<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $customers = User::customers($request);
    return CustomerResource::collection($customers);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
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

  /**
   * Display the specified resource.
   */
  public function show($id, Request $request)
  {
    $customer = User::find($id);
    return new CustomerResource($customer);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update($id, CustomerRequest $request)
  {
    $customer = User::findOrFail($id);
    $customer->update($request->all());
    return response()->json([
      'status'  => 'success',
      'message' => 'Customer record updated successfully.',
      'data'    => ['customerId' => $customer->id]
    ]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $customer = User::find($id);
    $customer->delete();
    return response()->json([
      'message' => "Customer $customer->name deleted successfully."
    ]);
  }
}
