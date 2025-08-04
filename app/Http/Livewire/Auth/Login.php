<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{

    public $email = '';
    public $password = '';
    public $remember_me = false;

    protected $rules = [
        'email' => 'required|email:rfc,dns',
        'password' => 'required|min:6',
    ];

    //This mounts the default credentials for the admin. Remove this section if you want to make it public.
    public function mount()
    {
        Log::info('Login page accessed', [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl()
        ]);

        if (auth()->user()) {
            Log::info('User already authenticated, redirecting to dashboard', [
                'user_id' => auth()->id(),
                'email' => auth()->user()->email
            ]);
            return redirect()->intended('/dashboard');
        }
    }

    public function login()
    {
        Log::info('Login attempt started', [
            'email' => $this->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        try {
            $credentials = $this->validate();
            Log::info('Login validation passed', ['email' => $this->email]);

            // Check if user exists
            $user = User::where('email', $this->email)->first();
            if (!$user) {
                Log::warning('Login failed - user not found', [
                    'email' => $this->email,
                    'ip' => request()->ip()
                ]);
                return $this->addError('email', trans('auth.failed'));
            }

            Log::info('User found in database', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_active' => $user->status ?? 'unknown'
            ]);

            // Try authentication
            if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
                Log::info('Authentication successful', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'remember_me' => $this->remember_me
                ]);

                auth()->login($user, $this->remember_me);
                
                Log::info('User logged in successfully, redirecting to dashboard', [
                    'user_id' => auth()->id(),
                    'intended_url' => session()->get('url.intended', '/dashboard')
                ]);

                return redirect()->intended('/dashboard');
            } else {
                Log::warning('Authentication failed - invalid credentials', [
                    'email' => $this->email,
                    'ip' => request()->ip(),
                    'password_provided' => !empty($this->password),
                    'password_length' => strlen($this->password)
                ]);
                return $this->addError('email', trans('auth.failed'));
            }
        } catch (\Exception $e) {
            Log::error('Login process exception', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->addError('email', 'An error occurred during login. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
