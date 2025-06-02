<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Profile extends Component
{
    public User $user;
    public $showSavedAlert = false;
    public $showDemoNotification = false;

    public function rules() {
        return [
            'user.name' => 'required|string|max:100',
            'user.email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'user.phone_number' => 'nullable|string|size:10',
            'user.working_days' => 'nullable|string|max:255', // Could be comma separated days
            'user.working_hours_start' => 'nullable|date_format:H:i:s',
            'user.working_hours_end' => 'nullable|date_format:H:i:s',
        ];
    }

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function save()
    {

        $this->validate();

        $this->user->save();

        $this->showSavedAlert = true;
    }

    public function render()
    {
        $user =  auth()->user();
        $user->working_days = $user->working_days ? explode(',', $user->working_days) : [];
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $roles = Role::all(); // Or apply any filtering logic
        return view('livewire.profile', compact('user','days','roles'));
    }
}
