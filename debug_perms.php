<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking Permissions...\n";

$permissions = ['manage_services', 'manage_packages'];
foreach ($permissions as $p) {
    $exists = Permission::where('name', $p)->exists();
    echo "Permission '$p' exists: " . ($exists ? 'YES' : 'NO') . "\n";
}

$roles = ['Owner', 'Admin', 'Vendor'];
foreach ($roles as $rName) {
    $role = Role::where('name', $rName)->first();
    if ($role) {
        echo "Role '$rName' found (Guard: {$role->guard_name}). Permissions:\n";
        foreach ($role->permissions as $p) {
            echo " - " . $p->name . "\n";
        }
    } else {
        echo "Role '$rName' NOT FOUND.\n";
    }
}

$ownerUser = User::where('email', 'owner@event.com')->first();
if ($ownerUser) {
    echo "\nUser owner@event.com roles: " . implode(', ', $ownerUser->getRoleNames()->toArray()) . "\n";
    echo "Can manage_services? " . ($ownerUser->can('manage_services') ? 'YES' : 'NO') . "\n";
} else {
    echo "\nUser owner@event.com not found.\n";
}
