<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class LiveServerPermissionFix extends Seeder
{
    /**
     * Fix permissions for live server
     *
     * @return void
     */
    public function run()
    {
        echo "🔧 Fixing live server permissions...\n\n";

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

        // Get or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Give admin role all permissions
        $allPermissions = array_merge(['access_admin_panel'], $permissions);
        $adminRole->syncPermissions($allPermissions);
        echo "✅ Admin role has all permissions\n";

        // Get or create super admin role  
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminRole->syncPermissions($allPermissions);
        echo "✅ Super admin role has all permissions\n";

        // Get or create manager role
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions($allPermissions);
        echo "✅ Manager role has all permissions\n";

        // Find all existing users and give them admin panel access
        $users = User::all();
        
        foreach ($users as $user) {
            // Give access_admin_panel permission to all existing users
            if (!$user->hasPermissionTo('access_admin_panel')) {
                $user->givePermissionTo('access_admin_panel');
                echo "✅ Gave admin panel access to: {$user->email}\n";
            }

            // If user has admin/manager role, give them all permissions
            if ($user->hasRole(['admin', 'manager', 'super_admin'])) {
                $user->syncPermissions($allPermissions);
                echo "✅ Gave full permissions to: {$user->email}\n";
            } else {
                // For other users, give them basic permissions
                $user->syncPermissions([
                    'access_admin_panel',
                    'view_users', 'view_customers', 'view_orders', 'view_tickets'
                ]);
                echo "✅ Gave basic permissions to: {$user->email}\n";
            }
        }

        echo "\n🎉 Live server permissions fixed successfully!\n";
        echo "All users can now access the admin panel.\n";
        echo "Users with admin/manager roles have full permissions.\n";
    }
}
