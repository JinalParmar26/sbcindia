<x-layouts.base>


    @if(in_array(request()->route()->getName(), [
        'dashboard', 'customers','customers.create','customers.edit',
        'products','products.create','products.edit',
        'orders','orders.create','orders.edit',
        'leads','leads.create','leads.edit','leads.show',   {{-- 👈 Add this --}}
        'tickets','tickets.create','tickets.edit',
        'staff','staff.actions','staff.tickets',
        'staff.locations','staff.locations.live','staff.locations.data','staff.locations.show','staff.locations.trail',
        'profile','profile-example',
        'users','users.create','users.edit',
        'roles','roles.index','roles.create','roles.show','roles.edit','role-permissions',
        'bootstrap-tables','transactions','buttons','forms','modals','notifications','typography','upgrade-to-pro',
        'location.index','location.live.tracking','location.live.data','location.user.show','location.user.trail',
        'location.user.data','location.user.summary','location.user.export','location.users.with.data','location.cleanup',
        'staff_attendance.index','user.attendance.detail','user.locations.index','user-location.show',"salary.monthly","salary.detail"     
    ]))

    {{-- Nav --}}
    @include('layouts.nav')
    {{-- SideNav --}}
    @include('layouts.sidenav')
    <main class="content">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- TopBar --}}
        @include('layouts.topbar')
        @yield('content')
        {{ $slot ?? '' }}
        {{-- Footer --}}
        @include('layouts.footer')
    </main>

    @elseif(in_array(request()->route()->getName(), ['register', 'register-example', 'login', 'login-example',
    'forgot-password', 'forgot-password-example', 'reset-password','reset-password-example']))

    @yield('content')
    {{-- Footer --}}
    @include('layouts.footer2')


    @elseif(in_array(request()->route()->getName(), ['404', '500', 'lock']))

    @yield('content')

    @endif
</x-layouts.base>
