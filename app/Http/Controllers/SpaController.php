<?php

namespace App\Http\Controllers;

use App\Models\Config;

class SpaController extends Controller
{
  /**
   * Get the SPA view.
   */
  public function __invoke()
  {
    $settings = Config::all()->pluck('value', 'key')->toArray();
    return view('spa', ['settings' => $settings]);
  }
}
