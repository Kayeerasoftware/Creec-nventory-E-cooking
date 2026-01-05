<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$techs = \App\Models\Technician::take(5)->get(['name', 'email', 'phone', 'cohort_number']);
foreach ($techs as $t) {
    echo "Name: " . $t->name . "\n";
    echo "Email: " . $t->email . "\n";
    echo "Phone: " . $t->phone . "\n";
    echo "Cohort: " . $t->cohort_number . "\n\n";
}
