@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Salary Details - {{ $user->name }} ({{ $month }})</h2>

    <form method="GET" action="{{ route('salary.detail', [$user->id, $month]) }}" class="mb-3">
        <label>Select Month:</label>
        <input type="month" name="month" value="{{ $month }}">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table table-bordered">
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
            @foreach($dates as $day)
            <tr>
                <td>{{ $day['date'] }}</td>
                <td>{{ $day['present'] }}</td>
                <td>{{ $day['main_hours'] }}</td>
                <td>{{ $day['extra_hours'] }}</td>
                <td>{{ $day['service_hours'] }}</td>
                <td>₹{{ $day['main_salary'] }}</td>
                <td>₹{{ $day['extra_salary'] }}</td>
                <td>₹{{ $day['service_salary'] }}</td>
                <td><strong>₹{{ $day['final_salary'] }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
