<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Vendor Category Analysis ===\n\n";

// All vendors by category
echo "All Vendors by Category:\n";
$vendors = \App\Models\Vendor::select('id', 'brand_name', 'category', 'user_id')->get();
$grouped = $vendors->groupBy('category');
foreach ($grouped as $cat => $vends) {
    echo "  $cat: " . $vends->count() . " vendors\n";
}

echo "\n=== Catalog Items Analysis ===\n\n";

// Catalog items by show_on_landing
$total = \App\Models\VendorCatalogItem::count();
$showOnLanding = \App\Models\VendorCatalogItem::where('show_on_landing', true)->count();
echo "Total catalog items: $total\n";
echo "With show_on_landing=true: $showOnLanding\n\n";

// Catalog items by vendor category
echo "Catalog items (show_on_landing=true) by Vendor Category:\n";
$items = \App\Models\VendorCatalogItem::with('vendor')
    ->where('show_on_landing', true)
    ->get();

$byCategory = $items->groupBy(function($item) {
    return $item->vendor->category ?? 'NULL';
});

foreach ($byCategory as $cat => $catItems) {
    echo "  $cat: " . $catItems->count() . " items\n";
}

echo "\n=== Owner ID Check ===\n\n";

// Check owner_id distribution
$itemsWithOwner = \App\Models\VendorCatalogItem::with('vendor.user')
    ->where('show_on_landing', true)
    ->get();

$byOwner = $itemsWithOwner->groupBy(function($item) {
    return $item->vendor->user->owner_id ?? 'NULL';
});

echo "Catalog items by vendor's user owner_id:\n";
foreach ($byOwner as $ownerId => $ownerItems) {
    echo "  owner_id=$ownerId: " . $ownerItems->count() . " items\n";
}

// Show client's owner_id for comparison
echo "\n=== Sample Client Check ===\n";
$client = \App\Models\User::where('role', 'client')->first();
if ($client) {
    echo "Sample client: {$client->email}, owner_id: " . ($client->owner_id ?? 'NULL') . "\n";
}
