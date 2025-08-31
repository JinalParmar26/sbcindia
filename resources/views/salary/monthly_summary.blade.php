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
                    <li class="breadcrumb-item"><a href="#">Staff</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Monthly Salary</li>
                </ol>
            </nav>
            <h2 class="h4">Monthly Salary Summary</h2>
            <p class="mb-0">Overview of staff salaries for <strong>{{ \Carbon\Carbon::parse($month)->format('F Y') }}</strong>.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <form method="GET" action="{{ route('salary.monthly') }}" class="d-flex">
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
                    <th>Name</th>
                    <th>Role</th>
                    <th>Present Days</th>
                    <th>Absent Days</th>
                    <th>Main Salary</th>
                    <th>Extra Salary</th>
                    <th>Final Salary</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr>
                    <td>
                        <span class="fw-bold">{{ $row['name'] }}</span>
                    </td>
                    <td><span class="fw-normal">{{ $row['role'] }}</span></td>
                    <td><span class="fw-normal">{{ $row['present_days'] }}</span></td>
                    <td><span class="fw-normal">{{ $row['absent_days'] }}</span></td>
                    <td><span class="fw-bold text-success">₹{{ $row['main_salary'] }}</span></td>
                    <td><span class="fw-bold text-warning">₹{{ $row['extra_salary'] }}</span></td>
                    <td><span class="fw-bold text-dark">₹{{ $row['final_salary'] }}</span></td>
                    <td>
                        <a href="{{ route('salary.detail', [$row['id'], $month]) }}"
                           class="btn btn-sm btn-outline-gray-600">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No salary records found for this month.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
