<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchableUserController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $role = $request->input('supplier') ? 'supplier' : 'customer';
    $items = User::search($request->input('q'), $role);
    if ($items->IsEmpty()) {
      $items = [[
        'id'    => 0,
        'name'  => $request->input('q')
      ]];
    }

    return response()->json($items);
  }
}
