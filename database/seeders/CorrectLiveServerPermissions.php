<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CorrectLiveServerPermissions extends Seeder
{
    /**
     * Fix permissions correctly for live server
     * Only admin/manager/super_admin should access admin panel
     *
     * @return void
     */
    public function run()
    {
        echo "🔧 Correcting live server permissions...\n\n";

        // Create the access_admin_panel permission if it doesn't exist
        $adminPanelPermission = Permission::firstOrCreate(['name' => 'access_admin_panel']);
        echo "✅ Created access_admin_panel permission\n";

        // Create all other permissions
        $permissions = [
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_customers', 'create_customers', 'edit_customers', 'delete_customers',
            'view_orders', 'create_orders', 'edit_orders', 'delete_orders',
            'view_tickets', 'create_tickets', 'edit_tickets', 'delete_tickets',
            'view_staff', 'create_staff', 'edit_staff', 'manage_attendance',
            'view_marketing', 'create_marketing', 'edit_marketing', 'delete_marketing',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        echo "✅ Created all module permissions\n";

        // Get or create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);

        // Give admin roles all permissions including admin panel access
        $allPermissions = array_merge(['access_admin_panel'], $permissions);
        $adminRole->syncPermissions($allPermissions);
        $superAdminRole->syncPermissions($allPermissions);
        $managerRole->syncPermissions($allPermissions);
        
        echo "✅ Admin, Super Admin, and Manager roles have all permissions\n";

        // Staff role gets NO admin panel access
        $staffRole->syncPermissions([]);
        echo "✅ Staff role has NO admin panel access\n";

        // Fix all existing users
        $users = User::all();
        
        foreach ($users as $user) {
            // Remove ALL permissions first
            $user->syncPermissions([]);
            
            // Only give admin panel access to admin/manager/super_admin roles
            if ($user->hasRole(['admin', 'manager', 'super_admin'])) {
                $user->syncPermissions($allPermissions);
                echo "✅ Gave full permissions to admin/manager: {$user->email}\n";
            } else {
                // Staff and other users get NO admin panel access
                echo "❌ Removed admin panel access from staff: {$user->email}\n";
            }
        }

        echo "\n🎉 Live server permissions corrected!\n";
        echo "✅ Only admin/manager/super_admin can access admin panel\n";
        echo "❌ Staff users CANNOT access admin panel (as intended)\n";
    }
}
