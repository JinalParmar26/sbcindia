<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

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
        if (env('IS_DEMO')) {
            $this->showDemoNotification = true;
            return;
        }

        $this->validate();

        $this->user->save();

        $this->showSavedAlert = true;
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
