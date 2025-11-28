<?php

use App\Models\User;
use App\Models\Vendor;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking Vendor Data...\n";

// Check Owner
$owner = User::where('email', 'owner@event.com')->first();
if ($owner) {
    echo "Owner found: {$owner->name} (ID: {$owner->id})\n";
    $vendor = Vendor::where('user_id', $owner->id)->first();
    if ($vendor) {
        echo " - Vendor Profile Found: {$vendor->brand_name} (ID: {$vendor->id})\n";
    } else {
        echo " - NO Vendor Profile Found for Owner.\n";
    }
} else {
    echo "Owner user not found.\n";
}

// Check Admin
$admin = User::where('email', 'admin@event.com')->first();
if ($admin) {
    echo "\nAdmin found: {$admin->name} (ID: {$admin->id})\n";
    echo " - Owner ID: " . ($admin->owner_id ?? 'NULL') . "\n";
    
    if ($admin->owner_id) {
        $ownerOfAdmin = User::find($admin->owner_id);
        if ($ownerOfAdmin) {
            echo " - Owner of Admin: {$ownerOfAdmin->name} (ID: {$ownerOfAdmin->id})\n";
            $vendorOfOwner = Vendor::where('user_id', $ownerOfAdmin->id)->first();
            if ($vendorOfOwner) {
                echo " - Vendor Profile of Owner: {$vendorOfOwner->brand_name} (ID: {$vendorOfOwner->id})\n";
            } else {
                echo " - NO Vendor Profile Found for Owner of Admin.\n";
            }
        } else {
            echo " - Owner user not found for ID {$admin->owner_id}.\n";
        }
    }
} else {
    echo "Admin user not found.\n";
}
