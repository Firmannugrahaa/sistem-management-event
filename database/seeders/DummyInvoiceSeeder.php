<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\Venue;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyInvoiceSeeder extends Seeder
{
    public function run()
    {
        // Create SuperUser if not exists
        $superUser = User::firstOrCreate(
            ['email' => 'SuperUser@event.com'],
            [
                'name' => 'Super User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        // Ensure password is set correctly even if user existed
        $superUser->password = Hash::make('password');
        $superUser->save();
        
        if (method_exists($superUser, 'assignRole')) {
            $superUser->assignRole('SuperUser');
        }

        // 1. Create Dummy Clients
        $clients = [];
        for ($i = 1; $i <= 5; $i++) {
            $clients[] = User::firstOrCreate(
                ['email' => "dummyclient{$i}@example.com"],
                [
                    'name' => "Dummy Client {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        // Assign Role 'User' (Client) if Spatie Permission is used
        foreach ($clients as $client) {
            // Check if method exists to avoid errors if Spatie is not fully set up or trait missing
            if (method_exists($client, 'hasRole') && !$client->hasRole('User')) {
                $client->assignRole('User');
            }
        }

        // 2. Create Dummy Venue (if needed)
        $venue = Venue::firstOrCreate(
            ['name' => 'Dummy Grand Ballroom'],
            [
                'address' => 'Jl. Dummy No. 123',
                'capacity' => 500,
                'price' => 15000000,
            ]
        );

        // 3. Create Dummy Events & Invoices
        $statuses = ['Paid', 'Unpaid', 'Overdue', 'Draft'];
        
        foreach ($clients as $index => $client) {
            $eventDate = Carbon::now()->addDays(rand(10, 60));
            
            $event = Event::create([
                'user_id' => $client->id,
                'venue_id' => $venue->id,
                'event_name' => "Wedding of " . $client->name,
                'description' => "Dummy wedding event for screenshot purposes.",
                'start_time' => $eventDate->copy()->setTime(10, 0),
                'end_time' => $eventDate->copy()->setTime(14, 0),
                'status' => 'Confirmed',
                'client_name' => $client->name,
                'client_phone' => '08123456789',
                'client_email' => $client->email,
                'client_address' => 'Jakarta',
            ]);

            // Create Invoice
            $status = $statuses[$index % count($statuses)];
            $amount = rand(10000000, 50000000);
            
            Invoice::create([
                'event_id' => $event->id,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($event->id, 4, '0', STR_PAD_LEFT),
                'issue_date' => Carbon::now()->subDays(rand(1, 10)),
                'due_date' => Carbon::now()->addDays(rand(5, 20)),
                'total_amount' => $amount,
                'status' => $status,
            ]);
        }
    }
}
