<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions if they don't exist
        $permissions = [
            'access_admin_panel',
            'create_leads',
            'edit_leads', 
            'view_leads',
            'delete_leads',
            'create_marketing',
            'edit_marketing',
            'view_marketing',
            'delete_marketing',
            'create_users',
            'edit_users',
            'view_users',
            'delete_users',
            'create_customers',
            'edit_customers',
            'view_customers',
            'delete_customers',
            'create_products',
            'edit_products',
            'view_products',
            'delete_products',
            'create_orders',
            'edit_orders',
            'view_orders',
            'delete_orders',
            'create_tickets',
            'edit_tickets',
            'view_tickets',
            'delete_tickets',
            'view_staff',
            'create_staff',
            'edit_staff',
            'delete_staff',
            'manage_attendance',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Find or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Give admin role all permissions
        $adminRole->syncPermissions($permissions);

        // Find or create manager role
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        
        // Give manager role only view permissions
        $managerPermissions = [
            'access_admin_panel',
            'view_leads',
            'view_marketing', 
            'view_users',
            'view_customers',
            'view_products',
            'view_orders',
            'view_tickets',
            'view_staff',
        ];
        
        $managerRole->syncPermissions($managerPermissions);

        // Find admin user and assign admin role
        $adminUser = User::where('email', 'admin@sbcerp.com')->first();
        
        if ($adminUser) {
            $adminUser->assignRole('admin');
            $this->command->info('Admin user permissions updated successfully.');
        } else {
            $this->command->error('Admin user not found with email: admin@sbcerp.com');
        }

        $this->command->info('Manager role created with view-only permissions.');
    }
}
