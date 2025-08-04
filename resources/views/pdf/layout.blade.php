<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Report' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .generated-info {
            font-size: 10px;
            color: #888;
        }
        .filters {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .filters h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .filter-item {
            margin-bottom: 5px;
        }
        .filter-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-info {
            background-color: #17a2b8;
        }
        .badge-primary {
            background-color: #007bff;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .stat-item {
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin: 0 5px;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $title ?? 'Report' }}</div>
        <div class="subtitle">Generated on {{ $generated_at ?? date('Y-m-d H:i:s') }}</div>
    </div>

    @if(!empty($filters) && count($filters) > 0)
        <div class="filters">
            <h3>Applied Filters:</h3>
            @foreach($filters as $key => $value)
                @if($value && $value != 'all')
                    <div class="filter-item">
                        <span class="filter-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                        {{ is_array($value) ? implode(', ', $value) : $value }}
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    @yield('content')

    <div class="footer">
        <p>This report was generated automatically by the ERP System on {{ date('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
