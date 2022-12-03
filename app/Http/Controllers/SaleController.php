<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use Exception;
use Illuminate\Http\Request;

class SaleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $order_column = $request->get('orderBy');
    if (!in_array($order_column, ['id', 'name', 'email', 'phone', 'created_at'])) {
      $order_column = 'id';
    }
    $q =  '%' . $request->input('q') . '%';
    $items = Sale::orderBy($order_column, $request->boolean('orderDesc') ? 'desc' : 'asc')
      ->paginate($request->get('per_page'));
    return SaleResource::collection($items);
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
  public function store(SaleRequest $request)
  {
    try {
      $sale = new Sale();
      $sale->customer_id = $request->input('customer_id') ?: 0;
      $sale->employee_id = auth()->id();
      $sale->sale_time = $request->input('sale_time') ?: Date('Y-m-d H:i:s');
      $items = $request->input('invoiceItems');
      if (!$items) {
        return response()->json(['status' => 'error', 'data' => [], 'message' => 'One item required'], 422);
      }
      $sale->save();
      $sale_items = [];
      foreach ($items as $item) {
        $sale_items[] = [
          'item_id'    => $item['item_id'],
          'quantity'   => $item['quantity'],
          'cost_price' => $item['cost_price'],
          'sale_price' => $item['sale_price'],
        ];
      }
      $sale->items()->createMany($sale_items);
      return response()->json(['status' => 'success', 'data' => ['sale_id' => $sale->id]]);
    } catch (Exception $ex) {
      $sale->delete();
      return response()->json(['status' => 'error', 'data' => [], 'message' => $ex->getMessage()], 422);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Sale  $sale
   * @return \Illuminate\Http\Response
   */
  public function show(Sale $sale)
  {
    return new SaleResource($sale);
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
   * @param  \App\Models\Sale  $sale
   * @return \Illuminate\Http\Response
   */
  public function destroy(Sale $sale)
  {
    $sale->items()->delete();
    $sale->delete();
    return response()->json([
      'status'  => 'success',
      'message' => 'Invoice deleted successfully.',
      'data'    => []
    ]);
  }
}
