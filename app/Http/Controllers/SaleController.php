<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Models\SaleItem;
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
    $records = Sale::with('customer', 'creator', 'items', 'items.item', 'payments')
      ->listWithFilters($request);
    return SaleResource::collection($records);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  App\Http\Requests\SaleRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(SaleRequest $request)
  {
    try {
      $sale = new Sale();
      $sale->customer_id = $request->input('customerId') ?: 0;
      $sale->sale_time = $request->input('saleTime') ?: Date('Y-m-d H:i:s');
      $items = $request->input('invoiceItems');
      if (!$items) {
        return response()->json(['status' => 'error', 'data' => [], 'message' => 'Please add one or more items.'], 422);
      }
      $sale->discount_amount = $request->input('discountAmount') ?: 0;
      $sale->comments = $request->input('comments') ?: '';
      $sale->save();

      $saleItems = [];
      foreach ($items as $item) {
        $saleItems[] = [
          'item_id'    => $item['itemId'],
          'quantity'   => $item['quantity'],
          'cost_price' => $item['costPrice'],
          'sale_price' => $item['salePrice'],
        ];
      }
      $sale->items()->createMany($saleItems);

      $paidAmount = $request->input('amountPaid') ?: 0;
      if($paidAmount > 0)
      {
        $sale->payments()->create([
          'user_id'       => $sale->customer_id,
          'payment_time'  => $sale->sale_time,
          'payment_mode'  => 'Cash',
          'amount'        => $paidAmount,
        ]);
      }
      $sale->recalculate();

      return response()->json(['status' => 'success', 'message' => 'Sale record is created successfully.', 'data' => ['saleId' => $sale->id]]);
    } catch (Exception $ex) {
      $sale->delete();
      return response()->json(['status' => 'error', 'data' => [], 'message' => $ex->getMessage()], 422);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $saleId
   * @return \Illuminate\Http\Response
   */
  public function show(int $saleId)
  {
    $sale = Sale::with('customer', 'creator', 'items', 'items.item')->findOrFail($saleId);
    return new SaleResource($sale);
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
      if($request->input('addItem')){
        SaleItem::create([
          'sale_id'     => $sale->id,
          'item_id'     => $request->input('itemId', 0),
          'quantity'    => intval($request->input('quantity', 0)),
          'sale_price'  => floatval($request->input('price', 0))
        ]);
        $sale->recalculate();
      }
      else{
        $items = (array) $request->input('invoiceItems');
        if (!$items) {
          return response()->json(['status' => 'error', 'data' => [], 'message' => 'One item required'], 422);
        }
        $lineItemsIds = [];
        foreach ($items as $item) {
          if(empty($item['id'])){
            $saleItem = SaleItem::create([
              'sale_id'     => $sale->id,
              'item_id'     => intval($item['itemId']),
              'quantity'    => intval($item['quantity']),
              'cost_price'  => floatval($item['costPrice']),
              'sale_price'  => floatval($item['salePrice'])
            ]);
            $lineItemsIds[] = $saleItem->id;
          }
          else{
            SaleItem::where('id', $item['id'])->update([
              'quantity'    => intval($item['quantity']),
              'sale_price'  => floatval($item['salePrice'])
            ]);
            $lineItemsIds[] = $item['id'];
          }
        }
        $sale->items()->whereNotIn('id', $lineItemsIds)->delete();
        $sale->discount_amount = $request->input('discountAmount') ?: 0;
        $sale->comments = $request->input('comments') ?: '';
        $sale->recalculate();
      }
      return response()->json(['status' => 'success', 'message' => 'Invoice updated successfully.', 'data' => ['saleId' => $sale->id]]);
    } catch (Exception $ex) {
      return response()->json(['status' => 'error', 'data' => [], 'message' => $ex->getMessage()], 422);
    }
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
      'message' => 'Sale record deleted successfully.',
      'data'    => []
    ]);
  }
}
