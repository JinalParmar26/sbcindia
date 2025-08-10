<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfExportService;


class UserController extends Controller
{
    protected $pdfExportService;

    public function __construct(PdfExportService $pdfExportService)
    {
        $this->pdfExportService = $pdfExportService;
    }

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
            'profile_photo' => 'nullable|image|max:2048',

        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['uuid'] = Str::uuid()->toString();
        $validated['working_days'] = isset($validated['working_days'])
            ? implode(',', $validated['working_days'])
            : null;

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public'); // saves to storage/app/public/profile_photos
            $validated['profile_photo'] = $path;
        }

        $user = User::create($validated);

        // Assign role
        $user->assignRole($request->input('role'));

        return redirect()->route('users.show', $user->uuid)->with('success', 'User created successfully.');
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

         // Generate QR code with text "Hello, Laravel 11!"
        $qrCode = QrCode::size(150)->generate(route('staff.visiting-card', $user->uuid));

        return view('users.show', compact('user', 'days', 'roles','qrCode'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail(); // Fetch user by UUID
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $roles = Role::all(); // Load all roles, or filter if needed

        $user->working_days = $user->working_days ? explode(',', $user->working_days) : [];

        return view('users.edit', compact('user', 'days', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        
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
            // dd($validated);

        if ($request->hasFile('profile_photo')) {
            // Optionally: delete old photo\


            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');
            $validated['profile_photo'] = $path;
        }


        $user->syncRoles($request->input('role'));
        $user->update($validated);
        
        // Refresh user from database to ensure uuid is available
        $user->refresh();

        return redirect()->route('users.show', $user->uuid)->with('success', 'User updated successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    {
        $user = auth()->user();// Fetch user or throw 404 if not found
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $roles = Role::all(); // Load all roles, or filter if needed

        $user->working_days = $user->working_days ? explode(',', $user->working_days) : [];

        return view('profile.edit', compact('user', 'days', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {

        $user = auth()->user();

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

        if ($request->hasFile('profile_photo')) {
            // Optionally: delete old photo
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');
            $validated['profile_photo'] = $path;
        }

        $user->syncRoles($request->input('role'));
        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

     /* Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPublicProfile($uuid)
    {
        // Fetch user by uuid
        $user = User::where('uuid', $uuid)->firstOrFail();

        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $roles = Role::all(); // If needed in view

        // Decode working_days JSON to array, or empty array if null
        $user->working_days = $user->working_days ? explode(',', $user->working_days) : [];

        return view('users.showPublicProfile', compact('user', 'days', 'roles'));
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

    public function downloadQr($uuid)
    {
        $qrCode = QrCode::format('png')->size(150)->generate(route('staff.visiting-card', $uuid));
        return response($qrCode)->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment;');
    }


    public function showQr($uuid)
    {
        $qrCode = QrCode::format('png')->size(150)->generate(route('staff.visiting-card', $uuid));
        return response($qrCode)->header('Content-Type', 'image/png');
    }

    public function exportCsv(Request $request)
    {
        // Get filters from request
        $search = $request->get('search', '');
        $statusFilter = $request->get('status_filter', 'all');
        $roleFilter = $request->get('role_filter', 'all');

        // Build query with same logic as Livewire component
        $query = User::query()->with('roles');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($statusFilter !== 'all') {
            $query->where(function ($q) use ($statusFilter) {
                if ($statusFilter === 'active') {
                    $q->where('isActive', 1);
                } else {
                    $q->where('isActive', 0);
                }
            });
        }

        if ($roleFilter !== 'all') {
            $query->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        // Create CSV content
        $csvContent = $this->generateUsersCsvContent($users, $request->all());

        // Create filename
        $filename = 'users_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        // Return CSV response
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, must-revalidate');
    }

    private function generateUsersCsvContent($users, $filters)
    {
        $csv = [];
        
        // Add header with filters info
        $filterText = 'Users Export - Generated on: ' . now()->format('F d, Y \a\t H:i');
        $csv[] = [$filterText];
        
        $appliedFilters = [];
        if (!empty($filters['search'])) {
            $appliedFilters[] = "Search: " . $filters['search'];
        }
        if (!empty($filters['status_filter']) && $filters['status_filter'] !== 'all') {
            $appliedFilters[] = "Status: " . ucfirst($filters['status_filter']);
        }
        if (!empty($filters['role_filter']) && $filters['role_filter'] !== 'all') {
            $appliedFilters[] = "Role: " . $filters['role_filter'];
        }
        
        if (!empty($appliedFilters)) {
            $csv[] = ['Applied Filters: ' . implode(' | ', $appliedFilters)];
        }
        
        $csv[] = []; // Empty row
        
        // Add table headers
        $csv[] = ['Name', 'Email', 'Status', 'Roles', 'Date Created'];
        
        // Add data rows
        foreach ($users as $user) {
            $status = $user->isActive ? 'Active' : 'Inactive';
            $roles = $user->roles->pluck('name')->implode(', ') ?: 'No Role';
            
            $csv[] = [
                $user->name,
                $user->email,
                $status,
                $roles,
                $user->created_at->format('M d, Y')
            ];
        }
        
        $csv[] = []; // Empty row
        $csv[] = ['Total Users: ' . $users->count()];
        
        // Convert to CSV string
        $output = '';
        foreach ($csv as $row) {
            $output .= '"' . implode('","', $row) . '"' . "\n";
        }
        
        return $output;
    }

    /**
     * Export users to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = User::query();

        // Apply filters
        $filters = [];
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            $filters['search'] = $request->search;
        }

        if ($request->filled('role_filter') && $request->role_filter != 'all') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role_filter);
            });
            $filters['role_filter'] = $request->role_filter;
        }

        if ($request->filled('status_filter') && $request->status_filter != 'all') {
            if ($request->status_filter == 'active') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
            $filters['status_filter'] = $request->status_filter;
        }

        $users = $query->get();

        return $this->pdfExportService->generateUsersPdf($users, $filters);
    }

    /**
     * Export a single user to PDF.
     */
    public function exportSinglePdf($uuid)
    {
        $user = User::where('uuid', $uuid)->with(['roles', 'overtimeLogs', 'assignedTickets', 'orders'])->firstOrFail();
        
        return $this->pdfExportService->generateSingleUserPdf($user);
    }

    /**
     * Show staff visiting card management
     */
    public function manageVisitingCards()
    {
        $staff = User::where('isActive', true)->get();
        return view('users.visiting-cards', compact('staff'));
    }

    /**
     * Generate staff visiting card links
     */
    public function generateVisitingCardLinks()
    {
        $staff = User::where('isActive', true)->get();
        $links = [];

        foreach ($staff as $member) {
            $links[] = [
                'name' => $member->name,
                'email' => $member->email,
                'role' => $member->role ?? 'Staff',
                'card_url' => route('staff.visiting-card', $member->uuid),
                'profile_url' => route('staff.profile', $member->uuid),
                'uuid' => $member->uuid
            ];
        }

        return response()->json($links);
    }

    /**
     * Update staff profile for visiting card
     */
    public function updateStaffProfile(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'public_profile' => 'boolean'
        ]);

        $user->update($request->all());

        return redirect()->back()->with('success', 'Staff profile updated successfully.');
    }
}
