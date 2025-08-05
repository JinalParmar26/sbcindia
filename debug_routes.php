<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

// Debug route to check users with UUIDs
Route::get('/debug-users', function () {
    $users = User::whereNotNull('uuid')->take(10)->get(['id', 'name', 'email', 'uuid']);
    
    if ($users->isEmpty()) {
        return 'No users with UUIDs found. Let me create some...';
    }
    
    $output = "Users with UUIDs:\n\n";
    foreach ($users as $user) {
        $output .= "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, UUID: {$user->uuid}\n";
        $output .= "Visiting Card URL: " . route('staff.visiting-card', $user->uuid) . "\n";
        $output .= "Public Profile URL: " . route('showPublicProfile', $user->uuid) . "\n\n";
    }
    
    return nl2br($output);
});
