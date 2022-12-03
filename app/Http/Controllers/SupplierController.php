<?php

namespace App\Http\Controllers;

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
    return ($suppliers);
    //CustomerResource::collection;
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'name'      => 'required',
      'email'     => 'required',
      'phone'     => 'required',
      'city'      => 'required',
      'address'   => 'required',
      'province'  => 'required',
    ]);
    $supplier = new User($request->all());
    $supplier->is_supplier = true;
    $supplier->save();
    return response()->json('Supplier added successfully');
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
