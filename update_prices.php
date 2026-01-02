<?php

use Illuminate\Support\Facades\DB;

// Update parts with estimated prices in UGX
DB::table('parts')->update([
    'price' => DB::raw('FLOOR(5000 + (RAND() * 145000))')
]);

// Update appliances with estimated prices in UGX
DB::table('appliances')->update([
    'price' => DB::raw('FLOOR(200000 + (RAND() * 1800000))')
]);

echo "Prices updated successfully!\n";
echo "Parts updated: " . DB::table('parts')->whereNotNull('price')->count() . "\n";
echo "Appliances updated: " . DB::table('appliances')->whereNotNull('price')->count() . "\n";
