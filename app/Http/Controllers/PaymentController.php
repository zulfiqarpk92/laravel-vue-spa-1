<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Requests\SaleRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\SaleResource;
use App\Models\Payment;
use App\Models\Sale;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $order_column = $request->get('orderBy');
    if (!in_array($order_column, ['id', 'description', 'amount', 'created_at'])) {
      $order_column = 'id';
    }
    $q =  '%' . $request->input('q') . '%';
    $payments = Payment::orderBy($order_column, $request->boolean('orderDesc') ? 'desc' : 'asc')
      ->paginate($request->get('per_page'));
    return PaymentResource::collection($payments);
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
   * @param  App\Http\Requests\PaymentRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(PaymentRequest $request)
  {
    $payment = null;
    try {
      $payment = new Payment($request->all());
      // $payment->employee_id = auth()->id();
      $payment->save();
      return response()->json(['status' => 'success', 'data' => ['payment_id' => $payment->id]]);
    } catch (Exception $ex) {
      $payment && $payment->delete();
      return response()->json(['status' => 'error', 'data' => [], 'message' => $ex->getMessage()], 422);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Payment $payment
   * @return \Illuminate\Http\Response
   */
  public function show(Payment $payment)
  {
    return new SaleResource($payment);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Sale  $sale
   * @return \Illuminate\Http\Response
   */
  public function edit(Sale $sale)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Sale  $sale
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Sale $sale)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Payment $payment
   * @return \Illuminate\Http\Response
   */
  public function destroy(Payment $payment)
  {
    $payment->delete();
    return response()->json([
      'status'  => 'success',
      'message' => 'Payment deleted successfully.',
      'data'    => []
    ]);
  }
}
