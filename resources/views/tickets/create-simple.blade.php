<!DOCTYPE html>
<html>
<head>
    <title>Tickets Create - Debug</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Tickets Create Page - Debug Mode</h1>
    
    <p>Customers: {{ count($customers ?? []) }}</p>
    <p>Products: {{ count($products ?? []) }}</p>
    <p>Staff: {{ count($staff ?? []) }}</p>
    
    <h2>Create Ticket Form</h2>
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <div>
            <label>Subject:</label>
            <input type="text" name="subject" required>
        </div>
        
        <div>
            <label>Customer:</label>
            <select name="customer_id" required>
                <option value="">Select customer</option>
                @foreach($customers ?? [] as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        
        <button type="submit">Create Ticket</button>
    </form>
</body>
</html>
