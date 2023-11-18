<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
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
    $payments = Payment::with('user', 'creator')
      ->listWithFilters($request);
    return PaymentResource::collection($payments);
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
      $payment = new Payment();
      $payment->user_id = $request->input('userId') ?: 0;
      $payment->sale_id = $request->input('saleId') ?: 0;
      $payment->payment_time = $request->input('paymentTime') ?: date('Y-m-d H:i:s');
      $payment->payment_mode = $request->input('paymentMode') ?: '';
      $payment->description = $request->input('description') ?: '';
      $payment->reference = $request->input('reference') ?: '';
      $payment->amount = $request->input('amount') ?: 0;
      $payment->save();
      return response()->json(['status' => 'success', 'data' => ['paymentId' => $payment->id]]);
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
    return new PaymentResource($payment);
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
    try {
      $payment = new Payment();
      $payment->user_id = $request->input('userId') ?: 0;
      $payment->sale_id = $request->input('saleId') ?: 0;
      $payment->payment_time = $request->input('paymentTime') ?: date('Y-m-d H:i:s');
      $payment->payment_mode = $request->input('paymentMode') ?: '';
      $payment->description = $request->input('description') ?: '';
      $payment->reference = $request->input('reference') ?: '';
      $payment->amount = $request->input('amount') ?: 0;
      $payment->save();
      return response()->json(['status' => 'success', 'data' => ['paymentId' => $payment->id]]);
    } catch (Exception $ex) {
      $payment && $payment->delete();
      return response()->json(['status' => 'error', 'data' => [], 'message' => $ex->getMessage()], 422);
    }
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
