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
                                    <div class="mt-2">
                                        <button 
                                            class="btn btn-sm btn-primary calculate-salary-btn" 
                                            data-user="{{ $user->id }}" 
                                            data-date="{{ $date->format('Y-m-d') }}">
                                            Calculate Salary
                                        </button>
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
<!-- Salary Result Modal -->
<div class="modal fade" id="salaryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Salary Calculation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="salaryModalBody">
        Loading...
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("calculate-salary-btn")) {
        let userId = e.target.getAttribute("data-user");
        let date = e.target.getAttribute("data-date");

        fetch("{{ url('/salary/calculate') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",   // 👈 tell Laravel we expect JSON
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                user_id: userId,
                date: date
            })
        })
        .then(async response => {
            if (!response.ok) {
                const errorText = await response.text(); // capture HTML error
                throw new Error(errorText);
            }
            return response.json();
        })
        .then(data => {
            document.getElementById("salaryModalBody").innerHTML = `
                <p><strong>Date:</strong> ${date}</p>
                <hr>
                <p><strong>Main Hours:</strong> ${data.main_hours}</p>
                <p><strong>Extra Hours (Service):</strong> ${data.extra_service_hours}</p>
                <p><strong>Extra Hours:</strong> ${data.extra_hours}</p>
                <p><strong>Total Hours:</strong> ${data.total_hours}</p>
                <hr>
                <p><strong>Main Salary:</strong> ${data.main_salary}</p>
                <p><strong>Extra Hours Salary (Service):</strong> ${data.extra_service_salary}</p>
                <p><strong>Extra Hours Salary:</strong> ${data.extra_salary}</p>
                <p><strong>Total Salary:</strong> ${data.total_salary}</p>
            `;
            new bootstrap.Modal(document.getElementById('salaryModal')).show();
        })
        .catch(error => {
            document.getElementById("salaryModalBody").innerHTML =
                `<p class="text-danger">Error: ${error.message}</p>`;
            new bootstrap.Modal(document.getElementById('salaryModal')).show();
        });

    }
});
</script>
@endsection
