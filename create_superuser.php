<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::firstOrCreate(
    ['email' => 'SuperUser@event.com'],
    [
        'name' => 'Super User',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]
);

$user->password = Hash::make('password');
$user->save();

if (!Role::where('name', 'SuperUser')->exists()) {
    Role::create(['name' => 'SuperUser']);
}

$user->assignRole('SuperUser');

echo "SuperUser created/updated successfully.\n";
