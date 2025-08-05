<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminPanelPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin panel access permission
        $permission = Permission::firstOrCreate([
            'name' => 'access_admin_panel',
            'guard_name' => 'web'
        ]);

        // Assign this permission to admin and manager roles by default
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        
        if ($adminRole) {
            $adminRole->givePermissionTo($permission);
        }
        
        if ($managerRole) {
            $managerRole->givePermissionTo($permission);
        }

        echo "Admin panel permission created and assigned to admin and manager roles.\n";
    }
}
