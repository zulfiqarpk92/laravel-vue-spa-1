<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\User;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $suppliers = User::suppliers($request);
    return SupplierResource::collection($suppliers);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(SupplierRequest $request)
  {
    $supplier = new User($request->validated());
    $supplier->role = 'user';
    $supplier->is_supplier = true;
    $supplier->save();
    return response()->json([
      'status'  => 'success',
      'message' => 'Supplier record added successfully.',
      'data'    => ['customer_id' => $supplier->id]
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $supplier = User::find($id);
    return response()->json($supplier);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $supplier = User::findOrFail($id);
    $supplier->update($request->all());
    return response()->json([
      'status'  => 'success',
      'message' => 'Supplier record updated successfully.',
      'data'    => ['customer_id' => $supplier->id]
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $supplier = User::find($id);
    $supplier->delete();
    return response()->json([
      'message' => "Supplier $supplier->name deleted successfully."
    ]);
  }
}
