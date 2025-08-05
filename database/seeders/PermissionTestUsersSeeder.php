<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionTestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create permissions if they don't exist
        $permissions = [
            'access_admin_panel',
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

        // Create test roles with specific permissions
        
        // 1. Super Admin (all permissions)
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminRole->syncPermissions($permissions);
        
        // 2. Ticket Manager (only ticket-related permissions)
        $ticketManagerRole = Role::firstOrCreate(['name' => 'ticket_manager']);
        $ticketManagerRole->syncPermissions([
            'access_admin_panel',
            'view_tickets', 'create_tickets', 'edit_tickets', 'delete_tickets'
        ]);
        
        // 3. Customer Support (view customers and tickets)
        $customerSupportRole = Role::firstOrCreate(['name' => 'customer_support']);
        $customerSupportRole->syncPermissions([
            'access_admin_panel',
            'view_customers', 'view_tickets', 'create_tickets', 'edit_tickets'
        ]);
        
        // 4. Order Manager (order and customer management)
        $orderManagerRole = Role::firstOrCreate(['name' => 'order_manager']);
        $orderManagerRole->syncPermissions([
            'access_admin_panel',
            'view_customers', 'view_orders', 'create_orders', 'edit_orders'
        ]);
        
        // 5. Staff Manager (staff and attendance only)
        $staffManagerRole = Role::firstOrCreate(['name' => 'staff_manager']);
        $staffManagerRole->syncPermissions([
            'access_admin_panel',
            'view_staff', 'create_staff', 'edit_staff', 'manage_attendance'
        ]);

        // Create test users
        
        // Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@test.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Ticket Manager User
        $ticketManager = User::firstOrCreate(
            ['email' => 'ticketmanager@test.com'],
            [
                'name' => 'Ticket Manager',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $ticketManager->assignRole('ticket_manager');

        // Customer Support User
        $customerSupport = User::firstOrCreate(
            ['email' => 'support@test.com'],
            [
                'name' => 'Customer Support',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $customerSupport->assignRole('customer_support');

        // Order Manager User
        $orderManager = User::firstOrCreate(
            ['email' => 'ordermanager@test.com'],
            [
                'name' => 'Order Manager',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $orderManager->assignRole('order_manager');

        // Staff Manager User
        $staffManager = User::firstOrCreate(
            ['email' => 'staffmanager@test.com'],
            [
                'name' => 'Staff Manager',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $staffManager->assignRole('staff_manager');

        // Limited Access User (only admin panel access, no module permissions)
        $limitedUser = User::firstOrCreate(
            ['email' => 'limited@test.com'],
            [
                'name' => 'Limited Access User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $limitedUser->givePermissionTo('access_admin_panel');

        echo "Permission test users created successfully!\n\n";
        echo "Test Users:\n";
        echo "1. superadmin@test.com (All permissions)\n";
        echo "2. ticketmanager@test.com (Only ticket management)\n";
        echo "3. support@test.com (Customers + Tickets)\n";
        echo "4. ordermanager@test.com (Customers + Orders)\n";
        echo "5. staffmanager@test.com (Staff management only)\n";
        echo "6. limited@test.com (Admin access but no module permissions)\n";
        echo "7. staff@test.com (No admin access - should get 403)\n\n";
        echo "Password for all: password123\n";
    }
}
