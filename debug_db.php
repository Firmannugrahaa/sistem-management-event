<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing DB Connection...\n";
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "Database connection successful.\n";
    $count = \Illuminate\Support\Facades\DB::table('users')->count();
    echo "Users count: $count\n";
} catch (\Exception $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
