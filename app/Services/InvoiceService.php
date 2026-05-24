<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class InvoiceService
{
  /**
   * Menghasilkan number.
   *
   * @param  string  $type
   * @return mixed
   */
  public static function generateNumber(string $type = 'INV')
  {
    $prefix = date('Y/m');

    $sequence = DB::transaction(function () use ($prefix, $type) {
      $key = $type . '/' . $prefix;

      $seq = DB::table('invoice_sequences')
              ->where('prefix', $key)
              ->lockForUpdate()
              ->first();

      if ($seq) {
        DB::table('invoice_sequences')
          ->where('prefix', $key)
          ->increment('last_number');
        return $seq->last_number + 1;
      } else {
        DB::table('invoice_sequences')->insert([
          'prefix' => $key,
          'last_number' => 1,
        ]);
        return 1;
      }
    });

    return $type . '/' . $prefix . '/' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
  }
}