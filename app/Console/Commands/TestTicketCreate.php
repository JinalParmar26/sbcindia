<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TestTicketCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:ticket-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test ticket create functionality';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing ticket create functionality...');
        
        // Try to get first user and login
        $user = User::first();
        if (!$user) {
            $this->error('No users found in database');
            return;
        }
        
        $this->info("Found user: {$user->name} ({$user->email})");
        
        // Test the controller directly
        try {
            $controller = new \App\Http\Controllers\TicketController();
            
            // Simulate authentication
            Auth::login($user);
            $this->info('User authenticated successfully');
            
            // Test the create method
            $response = $controller->create();
            $this->info('Controller create method executed successfully');
            $this->info('Response type: ' . get_class($response));
            
        } catch (\Exception $e) {
            $this->error('Error testing controller: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
        
        return Command::SUCCESS;
    }
}
