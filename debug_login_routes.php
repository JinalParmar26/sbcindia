// Login form debugging script
Route::get('/debug-login-form', function () {
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login Form Debug</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .container { max-width: 500px; margin: 0 auto; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input[type="email"], input[type="password"] { 
                width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; 
            }
            button { 
                background: #007bff; color: white; padding: 10px 20px; 
                border: none; border-radius: 4px; cursor: pointer; 
            }
            button:hover { background: #0056b3; }
            .debug-info { 
                background: #f8f9fa; padding: 15px; border-radius: 4px; 
                margin-top: 20px; font-family: monospace; 
            }
            .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; }
            .success { color: green; background: #e6ffe6; padding: 10px; border-radius: 4px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Login Form Debug Tool</h1>
            <p>Use this to test login functionality directly:</p>
            
            <form id="loginForm" method="POST" action="/debug-login-attempt">
                ' . csrf_field() . '
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="admin@sbcerp.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="remember_me" value="1"> Remember Me
                    </label>
                </div>
                
                <button type="submit">Test Login</button>
            </form>
            
            <div class="debug-info">
                <strong>Available Users:</strong><br>
                • admin@sbcerp.com<br>
                • manager@test.com<br>
                • staff@test.com<br>
                • staff1@test.com<br>
                • staff2@test.com<br><br>
                
                <strong>Tips:</strong><br>
                • Try common passwords like: admin, password, 123456, admin123<br>
                • Check browser console for JavaScript errors<br>
                • All attempts are logged for debugging
            </div>
        </div>
        
        <script>
            document.getElementById("loginForm").addEventListener("submit", function(e) {
                console.log("Form submission started");
                console.log("Email:", document.getElementById("email").value);
                console.log("Password length:", document.getElementById("password").value.length);
            });
        </script>
    </body>
    </html>';
    
    return $html;
});

Route::post('/debug-login-attempt', function () {
    Log::info('Debug login attempt received', [
        'email' => request('email'),
        'password_provided' => !empty(request('password')),
        'password_length' => strlen(request('password') ?? ''),
        'remember_me' => request('remember_me', false),
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent()
    ]);
    
    try {
        $email = request('email');
        $password = request('password');
        
        // Check if user exists
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            Log::warning('Debug login: User not found', ['email' => $email]);
            return back()->with('error', 'User not found with email: ' . $email);
        }
        
        Log::info('Debug login: User found', [
            'user_id' => $user->id,
            'email' => $user->email,
            'password_hash' => substr($user->password, 0, 20) . '...'
        ]);
        
        // Try authentication
        if (auth()->attempt(['email' => $email, 'password' => $password], request('remember_me'))) {
            Log::info('Debug login: SUCCESS', ['user_id' => $user->id]);
            return redirect('/dashboard')->with('success', 'Login successful!');
        } else {
            Log::warning('Debug login: Authentication failed', [
                'email' => $email,
                'password_check' => Hash::check($password, $user->password) ? 'MATCH' : 'NO_MATCH'
            ]);
            
            return back()->with('error', 'Invalid credentials. Check logs for details.');
        }
        
    } catch (\Exception $e) {
        Log::error('Debug login: Exception', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
});
