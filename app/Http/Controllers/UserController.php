<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $roles = Role::all(); // Or apply any filtering logic

        return view('users.create', compact('days', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'isActive' => 'boolean',
            'phone_number' => ['nullable', 'digits:10'],
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'working_hours_start' => 'nullable|date_format:H:i',
            'working_hours_end' => 'nullable|date_format:H:i|after:working_hours_start',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['uuid'] = Str::uuid()->toString();
        $validated['working_days'] = isset($validated['working_days'])
            ? implode(',', $validated['working_days'])
            : null;

        $user = User::create($validated);

        // Assign role
        $user->assignRole($request->input('role'));

        return redirect()->route('users')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        // Fetch user by uuid
        $user = User::where('uuid', $uuid)->firstOrFail();

        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $roles = Role::all(); // If needed in view

        // Decode working_days JSON to array, or empty array if null
        $user->working_days = $user->working_days ? explode(',', $user->working_days) : [];

        return view('users.show', compact('user', 'days', 'roles'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id); // Fetch user or throw 404 if not found
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $roles = Role::all(); // Load all roles, or filter if needed

        $user->working_days = $user->working_days ? explode(',', $user->working_days) : [];

        return view('users.edit', compact('user', 'days', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'isActive' => 'boolean',
            'phone_number' => ['nullable', 'digits:10'],
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'working_hours_start' => 'nullable|date_format:H:i',
            'working_hours_end' => 'nullable|date_format:H:i|after:working_hours_start',
        ]);

        if ($validated['password'] ?? false) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['working_days'] = isset($validated['working_days'])
            ? implode(',', $validated['working_days'])
            : null;

        $user->syncRoles($request->input('role'));
        $user->update($validated);

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users')->with('success', 'User deleted.');
    }
}
