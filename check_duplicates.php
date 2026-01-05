<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check for OTIM RICHARD
$otim = \App\Models\Technician::where('name', 'LIKE', '%OTIM RICHARD%')->get(['name', 'cohort_number', 'venue']);
echo "OTIM RICHARD count: " . $otim->count() . "\n";
foreach ($otim as $t) {
    echo "  - Cohort: " . $t->cohort_number . ", Venue: " . substr($t->venue, 0, 30) . "\n";
}

// Check cohort 26
$cohort26 = \App\Models\Technician::where('cohort_number', 26)->count();
echo "\nCohort 26 count: " . $cohort26 . "\n";
