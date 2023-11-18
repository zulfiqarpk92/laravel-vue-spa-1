<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Support\Str;

class SpaController extends Controller
{
  /**
   * Get the SPA view.
   */
  public function __invoke()
  {
    $settings = Config::all()->pluck('config_value', 'config_key')->toArray();
    $settings['currency_code'] = 'PKR';
    $settings['company'] = config('app.name');
    $settings['phone'] = '+92-344-1231231';
    $settings['email'] = 'contact@' . Str::of(config('app.url'))->replace('http://', '');
    $settings['website'] = config('app.url');
    return view('spa', ['settings' => $settings]);
  }
}
