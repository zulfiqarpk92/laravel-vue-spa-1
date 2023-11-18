<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Exception;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $records = Purchase::with('supplier', 'creator', 'items', 'items.item')
      ->listWithFilters($request);
    return PurchaseResource::collection($records);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  App\Http\Requests\PurchaseRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PurchaseRequest $request)
  {
    try {
      $purchase = new Purchase();
      $purchase->supplier_id = $request->input('supplierId') ?: 0;
      $purchase->purchase_time = $request->input('purchaseTime') ?: Date('Y-m-d H:i:s');
      // $purchase->discount_amount = $request->input('discountAmount') ?: 0;
      $purchase->comments = $request->input('comments') ?: '';
      $purchase->save();

      $purchaseItems = [];
      foreach ($request->input('purchaseItems') as $item) {
        $purchaseItems[] = [
          'item_id'         => $item['itemId'],
          'quantity'        => $item['quantity'],
          'purchase_price'  => $item['purchasePrice'],
        ];
      }
      $purchase->items()->createMany($purchaseItems);

      $paidAmount = $request->input('amountPaid') ?: 0;
      if($paidAmount > 0)
      {
        $purchase->payments()->create([
          'user_id'       => $purchase->customer_id,
          'payment_time'  => $purchase->sale_time,
          'payment_mode'  => 'Cash',
          'amount'        => $paidAmount,
        ]);
      }
      $purchase->recalculate();

      return response()->json(['status' => 'success', 'message' => 'Purchase record is created successfully.', 'data' => ['purchaseId' => $purchase->id]]);
    } catch (Exception $ex) {
      $purchase->delete();
      return response()->json(['status' => 'error', 'data' => [], 'message' => $ex->getMessage()], 422);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $purchaseId
   * @return \Illuminate\Http\Response
   */
  public function show(int $purchaseId)
  {
    $purchase = Purchase::with('supplier', 'creator', 'items', 'items.item')->findOrFail($purchaseId);
    return new PurchaseResource($purchase);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  App\Http\Requests\PurchaseRequest  $request
   * @param  \App\Models\Purchase  $purchase
   * @return \Illuminate\Http\Response
   */
  public function update(PurchaseRequest $request, Purchase $purchase)
  {
    try {
      if($request->input('addItem')){
        PurchaseItem::create([
          'sale_id'         => $purchase->id,
          'item_id'         => $request->input('itemId', 0),
          'quantity'        => intval($request->input('quantity', 0)),
          'purchase_price'  => floatval($request->input('purchasePrice', 0)),
        ]);
        $purchase->recalculate();
      }
      else{
        $purchase->supplier_id = $request->input('supplierId') ?: 0;
        $purchase->purchase_time = $request->input('purchaseTime') ?: Date('Y-m-d H:i:s');
        // $purchase->discount_amount = $request->input('discountAmount') ?: 0;
        $purchase->comments = $request->input('comments') ?: '';

        $lineItemsIds = [];
        foreach ($request->input('purchaseItems') as $item) {
          if(empty($item['id'])){
            $saleItem = PurchaseItem::create([
              'sale_id'         => $purchase->id,
              'item_id'         => intval($item['itemId']),
              'quantity'        => intval($item['quantity']),
              'purchase_price'  => floatval($item['purchasePrice']),
            ]);
            $lineItemsIds[] = $saleItem->id;
          }
          else{
            PurchaseItem::where('id', $item['id'])->update([
              'quantity'        => intval($item['quantity']),
              'purchase_price'  => floatval($item['purchasePrice']),
            ]);
            $lineItemsIds[] = $item['id'];
          }
        }
        $purchase->items()->whereNotIn('id', $lineItemsIds)->delete();

        $purchase->recalculate();
      }
      return response()->json(['status' => 'success', 'message' => 'Purchase record updated successfully.', 'data' => ['purchaseId' => $purchase->id]]);
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
  public function destroy(Purchase $sale)
  {
    $sale->items()->delete();
    $sale->delete();
    return response()->json([
      'status'  => 'success',
      'message' => 'Purchase record deleted successfully.',
      'data'    => []
    ]);
  }
}
