<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuPermissionHelper
{
    /**
     * Get menu items based on user permissions
     */
    public static function getAuthorizedMenuItems()
    {
        $user = Auth::user();
        $menuItems = [];

        // Dashboard - always available if user has admin panel access
        if ($user->can('access_admin_panel')) {
            $menuItems['dashboard'] = [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'permissions' => ['access_admin_panel']
            ];
        }

        // Users Management
        if ($user->can('view_users') || $user->can('create_users') || $user->can('edit_users') || $user->can('delete_users')) {
            $menuItems['users'] = [
                'name' => 'Users',
                'icon' => 'fas fa-users',
                'submenu' => []
            ];

            if ($user->can('view_users')) {
                $menuItems['users']['submenu']['list'] = [
                    'name' => 'View Users',
                    'route' => 'users',
                    'permission' => 'view_users'
                ];
            }

            if ($user->can('create_users')) {
                $menuItems['users']['submenu']['create'] = [
                    'name' => 'Create User',
                    'route' => 'users.create',
                    'permission' => 'create_users'
                ];
            }
        }

        // Customers Management
        if ($user->can('view_customers') || $user->can('create_customers') || $user->can('edit_customers') || $user->can('delete_customers')) {
            $menuItems['customers'] = [
                'name' => 'Customers',
                'icon' => 'fas fa-user-friends',
                'submenu' => []
            ];

            if ($user->can('view_customers')) {
                $menuItems['customers']['submenu']['list'] = [
                    'name' => 'View Customers',
                    'route' => 'customers',
                    'permission' => 'view_customers'
                ];
            }

            if ($user->can('create_customers')) {
                $menuItems['customers']['submenu']['create'] = [
                    'name' => 'Create Customer',
                    'route' => 'customers.create',
                    'permission' => 'create_customers'
                ];
            }
        }

        // Products Management
        if ($user->can('view_products') || $user->can('create_products') || $user->can('edit_products') || $user->can('delete_products')) {
            $menuItems['products'] = [
                'name' => 'Products',
                'icon' => 'fas fa-box',
                'submenu' => []
            ];

            if ($user->can('view_products')) {
                $menuItems['products']['submenu']['list'] = [
                    'name' => 'View Products',
                    'route' => 'products',
                    'permission' => 'view_products'
                ];
            }

            if ($user->can('create_products')) {
                $menuItems['products']['submenu']['create'] = [
                    'name' => 'Create Product',
                    'route' => 'products.create',
                    'permission' => 'create_products'
                ];
            }
        }

        // Orders Management
        if ($user->can('view_orders') || $user->can('create_orders') || $user->can('edit_orders') || $user->can('delete_orders')) {
            $menuItems['orders'] = [
                'name' => 'Orders',
                'icon' => 'fas fa-shopping-cart',
                'submenu' => []
            ];

            if ($user->can('view_orders')) {
                $menuItems['orders']['submenu']['list'] = [
                    'name' => 'View Orders',
                    'route' => 'orders',
                    'permission' => 'view_orders'
                ];
            }

            if ($user->can('create_orders')) {
                $menuItems['orders']['submenu']['create'] = [
                    'name' => 'Create Order',
                    'route' => 'orders.create',
                    'permission' => 'create_orders'
                ];
            }
        }

        // Tickets Management
        if ($user->can('view_tickets') || $user->can('create_tickets') || $user->can('edit_tickets') || $user->can('delete_tickets')) {
            $menuItems['tickets'] = [
                'name' => 'Tickets',
                'icon' => 'fas fa-ticket-alt',
                'submenu' => []
            ];

            if ($user->can('view_tickets')) {
                $menuItems['tickets']['submenu']['list'] = [
                    'name' => 'View Tickets',
                    'route' => 'tickets',
                    'permission' => 'view_tickets'
                ];
            }

            if ($user->can('create_tickets')) {
                $menuItems['tickets']['submenu']['create'] = [
                    'name' => 'Create Ticket',
                    'route' => 'tickets.create',
                    'permission' => 'create_tickets'
                ];
            }
        }

        // Leads/Marketing Management
        if ($user->can('view_marketing') || $user->can('create_marketing') || $user->can('edit_marketing') || $user->can('delete_marketing')) {
            $menuItems['leads'] = [
                'name' => 'Leads',
                'icon' => 'fas fa-bullseye',
                'submenu' => []
            ];

            if ($user->can('view_marketing')) {
                $menuItems['leads']['submenu']['list'] = [
                    'name' => 'View Leads',
                    'route' => 'leads',
                    'permission' => 'view_marketing'
                ];
            }

            if ($user->can('create_marketing')) {
                $menuItems['leads']['submenu']['create'] = [
                    'name' => 'Create Lead',
                    'route' => 'leads.create',
                    'permission' => 'create_marketing'
                ];
            }
        }

        // Staff Management
        if ($user->can('view_staff') || $user->can('create_staff') || $user->can('edit_staff') || $user->can('manage_attendance')) {
            $menuItems['staff'] = [
                'name' => 'Staff',
                'icon' => 'fas fa-user-tie',
                'submenu' => []
            ];

            if ($user->can('view_staff')) {
                $menuItems['staff']['submenu']['list'] = [
                    'name' => 'View Staff',
                    'route' => 'staff',
                    'permission' => 'view_staff'
                ];
            }

            if ($user->can('manage_attendance')) {
                $menuItems['staff']['submenu']['attendance'] = [
                    'name' => 'Attendance',
                    'route' => 'staff.actions',
                    'permission' => 'manage_attendance'
                ];
            }
        }

        return $menuItems;
    }

    /**
     * Check if user can access a specific menu item
     */
    public static function canAccessMenuItem($permissions)
    {
        $user = Auth::user();
        
        if (is_string($permissions)) {
            return $user->can($permissions);
        }
        
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($user->can($permission)) {
                    return true;
                }
            }
        }
        
        return false;
    }
}
