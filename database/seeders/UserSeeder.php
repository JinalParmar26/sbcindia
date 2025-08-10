<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        $admin = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'admin@sbcerp.com',
            'password' => Hash::make('admin123'),
            'isActive' => true,
            'phone_number' => '1234567890',
            'working_days' => 'Monday to Friday',
            'working_hours_start' => '09:00:00',
            'working_hours_end' => '17:00:00',
            'email_verified_at' => now(),
        ]);

        $admin->assignRole($role);
    }
}
