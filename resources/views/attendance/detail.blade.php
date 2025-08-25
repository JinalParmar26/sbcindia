@extends('layouts.app')

@section('content')
<div>
    {{-- Header / Breadcrumb --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff_attendance.index') }}">Today's Attendance</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Attendance Calendar</li>
                </ol>
            </nav>
            <h2 class="h4">{{ $user->name }} - Attendance Calendar</h2>
            <p class="mb-0">
                Viewing attendance for 
                <strong>{{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</strong>
            </p>
        </div>

        {{-- Back Button --}}
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('staff_attendance.index') }}" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                </svg>
                Back to Today's Attendance
            </a>
        </div>
    </div>

    {{-- Month/Year Filter --}}
    <form method="GET" id="monthYearForm" class="mb-4 d-flex gap-2 flex-wrap align-items-center">
        <select name="month" class="form-select" style="max-width:150px;" onchange="document.getElementById('monthYearForm').submit()">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                </option>
            @endforeach
        </select>

        <select name="year" class="form-select" style="max-width:150px;" onchange="document.getElementById('monthYearForm').submit()">
            @foreach(range(now()->year-5, now()->year+1) as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>

    {{-- Calendar Grid --}}
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($calendar as $week)
                <tr>
                    @foreach($week as $cell)
                        @if($cell)
                            @php
                                $date = $cell['date'];
                                $attendance = $cell['attendance'];
                                $today = \Carbon\Carbon::today();
                            @endphp
                            <td>
                                <div><strong>{{ $date->format('d') }}</strong></div>

                                @if($date->gt($today))
                                    {{-- Future date: show only date and day --}}
                                @elseif(!$attendance || !$attendance->check_in)
                                    <div class="text-danger">Absent</div>
                                @else
                                    <div>In: {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}</div>
                                    <div>
                                        Out: 
                                        @if($attendance->check_out)
                                            {{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}
                                        @else
                                            <span class="text-danger">Missing</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
