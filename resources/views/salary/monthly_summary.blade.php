@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Monthly Salary Summary ({{ $month }})</h2>

    <form method="GET" action="{{ route('salary.monthly') }}" class="mb-3">
        <label>Select Month:</label>
        <input type="month" name="month" value="{{ $month }}">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Present Days</th>
                <th>Absent Days</th>
                <th>Main Salary</th>
                <th>Extra Salary</th>
                <th>Final Salary</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['role'] }}</td>
                <td>{{ $row['present_days'] }}</td>
                <td>{{ $row['absent_days'] }}</td>
                <td>₹{{ $row['main_salary'] }}</td>
                <td>₹{{ $row['extra_salary'] }}</td>
                <td>
                    <a href="{{ route('salary.detail', [$row['id'], $month]) }}">
                        ₹{{ $row['final_salary'] }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
