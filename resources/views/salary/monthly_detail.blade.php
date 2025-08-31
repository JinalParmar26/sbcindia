@extends('layouts.app')

@section('content')
<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('salary.monthly') }}">Monthly Salary</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
            <h2 class="h4">Salary Details</h2>
            <p class="mb-0">
                {{ $user->name }} — <strong>{{ \Carbon\Carbon::parse($month)->format('F Y') }}</strong>
            </p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <form method="GET" action="{{ route('salary.detail', [$user->id, $month]) }}" class="d-flex">
                <input type="month" name="month" value="{{ $month }}" class="form-control me-2" style="max-width: 200px;">
                <button type="submit" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"></path>
                    </svg>
                    Filter
                </button>
            </form>
        </div>
    </div>

    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table user-table table-hover align-items-center">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Main Hours</th>
                    <th>Extra Hours</th>
                    <th>Service Hours</th>
                    <th>Main Salary</th>
                    <th>Extra Salary</th>
                    <th>Service Salary</th>
                    <th>Final Salary</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dates as $day)
                <tr>
                    <td><span class="fw-normal">{{ \Carbon\Carbon::parse($day['date'])->format('d M, Y') }}</span></td>
                    <td>
                        @if($day['present'] === 'Present')
                            <span class="badge bg-success">Present</span>
                        @elseif($day['present'] === 'Absent')
                            <span class="badge bg-danger">Absent</span>
                        @else
                            <span class="badge bg-secondary">{{ $day['present'] }}</span>
                        @endif
                    </td>
                    <td>{{ $day['main_hours'] }}</td>
                    <td>{{ $day['extra_hours'] }}</td>
                    <td>{{ $day['service_hours'] }}</td>
                    <td><span class="text-success fw-bold">₹{{ $day['main_salary'] }}</span></td>
                    <td><span class="text-warning fw-bold">₹{{ $day['extra_salary'] }}</span></td>
                    <td><span class="text-info fw-bold">₹{{ $day['service_salary'] }}</span></td>
                    <td><span class="text-dark fw-bold">₹{{ $day['final_salary'] }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">No records found for this month.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
