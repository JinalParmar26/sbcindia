<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class FixAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Find the admin user and set approval_required to 'no'
        $admin = User::where('email', 'admin@sbcerp.com')->first();
        
        if ($admin) {
            $admin->approval_required = 'no';
            $admin->save();
            
            echo "✅ Admin user approval status updated to 'no'\n";
        } else {
            echo "❌ Admin user not found\n";
        }
    }
}
