<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use App\Models\User;

class EmployeeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $employees = User::employees($request);
    return CustomerResource::collection($employees);
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
      'name' => 'required',
      'email' => 'required',
      'phone' => 'required',
      'city' => 'required',
      'address' => 'required',
      'province' => 'required',
    ]);

    $employee = new User($request->all());
    $employee->save();
    return response()->json(['status' => 'success', 'data' => ['customer_id' => $employee->id]]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = User::find($id);
    return response()->json(['status' => 'success', 'data' => $user]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
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
    $employee = User::findOrFail($id);
    $employee->update($request->all());
    return response()->json([
      'status'  => 'success',
      'message' => 'Customer record updated successfully.',
      'data'    => ['customer_id' => $employee->id]
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
    $employee = User::find($id);
    $employee->delete();
    return response()->json([
      'message' => "Employee $employee->name deleted successfully."
    ]);
  }
}
