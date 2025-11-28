<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FixOwnerIds extends Command
{
    protected $signature = 'fix:owner-ids';
    protected $description = 'Fix owner_id for seeded users';

    public function handle()
    {
        $owner = User::where('email', 'owner@event.com')->first();
        
        if (!$owner) {
            $this->error('Owner not found!');
            return;
        }

        $emails = [
            'admin.budi@event.com',
            'admin.siti@event.com',
            'staff.agus@event.com',
            'staff.dewi@event.com',
            'staff.eko@event.com',
            'staff.rina@event.com',
            'staff.joko@event.com'
        ];

        $count = User::whereIn('email', $emails)->update(['owner_id' => $owner->id]);
        
        $this->info("Updated {$count} users to belong to Owner ID: {$owner->id}");
    }
}
