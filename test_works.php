<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$works = \App\Models\Work::where('name_of_applicant', 'LIKE', '%RANJAN RAY%')
    ->orWhere('name_of_applicant', 'LIKE', '%SUTAPA BANERJEE%')
    ->orWhere('name_of_applicant', 'LIKE', '%BISWAJIT SAMADDAR%')
    ->get(['id', 'name_of_applicant', 'result', 'status', 'is_billing_done', 'payment_status', 'created_at'])
    ->toArray();

echo json_encode($works, JSON_PRETTY_PRINT);
