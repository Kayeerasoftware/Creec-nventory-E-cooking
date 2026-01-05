<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$count = \App\Models\Technician::count();
echo "Total technicians: $count\n";

$techs = \App\Models\Technician::take(5)->get(['name', 'cohort_number', 'venue']);
foreach ($techs as $t) {
    echo $t->name . " - Cohort: " . $t->cohort_number . "\n";
}
