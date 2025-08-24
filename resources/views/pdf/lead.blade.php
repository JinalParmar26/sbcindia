<!-- resources/views/pdf/lead-details.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lead Details</title>
<style>
    * { box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; margin: 10px; color: #000; }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; word-wrap: break-word; }
    th, td { border: 1px solid #000; padding: 6px; vertical-align: top; text-align: left; }
    th { background: #f0f0f0; }
    .challan { border: 1px solid #000; padding: 15px; width: 100%; }
    .section { margin-bottom: 12px; }
    .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
</style>
</head>
<body>

<div class="challan">

    <!-- Header -->
    <div class="header">
        <table style="border: none;">
            <tr>
                <td style="width: 40%; text-align: right; padding-right: 20px; border: none;">
                    <img src="{{ public_path('assets/img/logo/sbc-logo.webp') }}" alt="SBC Logo" style="width: 100px; height: auto;">
                </td>
                <td style="width: 60%; text-align: left; padding-left: 20px; border: none;">
                    <h1 style="margin: 0; font-size: 22px;">SBC COOLING PVT. LTD.</h1>
                    <p style="margin: 0; font-size: 14px;">Complete HVAC/R Solution</p>
                </td>
            </tr>
        </table>

        <div style="margin-top: 8px; text-align: center; font-size: 12px;">
            <p style="margin: 2px 0;">
                Unit 1: Plot No. 222-229, Part-1 &amp; Unit 2: Plot No. 90, Part-16, Om Textile Park, Post-Parab, Tal. Kamrej,
                Surat-394325, Gujarat, INDIA &nbsp; | &nbsp; Mo.: +91 98250 51800, +91 85111 48247
            </p>
        </div>

        <div style="margin-top: 10px; text-align: center;">
            <h2 style="margin: 0; font-size: 18px;">LEAD DETAILS</h2>
        </div>
    </div>

    <!-- Lead Info -->
    <div class="section">
        <table>
            <tr>
                <th>Lead Name</th>
                <td>{{ $lead->name }}</td>
                <th>Company</th>
                <td>{{ $lead->company_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Source</th>
                <td>{{ $lead->source ?? '-' }}</td>
                <th>Industry</th>
                <td>{{ $lead->industry ?? '-' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $lead->email ?? '-' }}</td>
                <th>Contact</th>
                <td>{{ $lead->contact ?? '-' }}</td>
            </tr>
            <tr>
                <th>WhatsApp</th>
                <td>{{ $lead->whatsapp_number ?? '-' }}</td>
                <th>Lead Owner</th>
                <td>{{ $lead->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td colspan="3">
                    {{ $lead->address ?? '-' }},
                    {{ $lead->area ?? '-' }},
                    {{ $lead->city ?? '-' }},
                    {{ $lead->state ?? '-' }},
                    {{ $lead->country ?? '-' }}
                    {{ $lead->pincode ? '- '.$lead->pincode : '' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Visit Logs -->
    <div class="section">
        <h3 style="margin: 6px 0;">Visit Logs</h3>
        <table>
            <tr>
                <th style="width: 12%;">Date</th>
                <th style="width: 12%;">Type</th>
                <th style="width: 10%;">Rating</th>
                <th style="width: 20%;">Notes</th>
                <th style="width: 20%;">Presented Products</th>
                <th style="width: 26%;">Location</th>
            </tr>
            @forelse($lead->visitLogs as $log)
                <tr>
                    <td>{{ $log->visit_date }}</td>
                    <td>{{ $log->lead_type }}</td>
                    <td>{{ $log->rating }}</td>
                    <td>{{ $log->notes }}</td>
                    <td>{{ $log->presented_products }}</td>
                    <td>
                        @if($log->latitude && $log->longitude)
                            📍 {{ $log->latitude }}, {{ $log->longitude }}  
                            <br>
                            {{ $log->location_address ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No visit logs available</td>
                </tr>
            @endforelse
        </table>
    </div>

</div>
</body>
</html>
