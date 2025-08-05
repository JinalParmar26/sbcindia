<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;

class UpdateUserUuids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-uuids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users that do not have UUIDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull('uuid')->orWhere('uuid', '')->get();
        
        if ($users->isEmpty()) {
            $this->info('All users already have UUIDs.');
            return;
        }
        
        $this->info("Found {$users->count()} users without UUIDs. Updating...");
        
        foreach ($users as $user) {
            $user->uuid = Str::uuid();
            $user->save();
            $this->info("Updated user {$user->id} ({$user->name}) with UUID: {$user->uuid}");
        }
        
        $this->info('UUID update completed!');
    }
}
