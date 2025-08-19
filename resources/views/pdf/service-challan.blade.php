<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Service Challan</title>
<style>
    * { box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; margin: 10px; color: #000; }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; word-wrap: break-word; }
    th, td { border: 1px solid #000; padding: 6px; vertical-align: top; text-align: left; }
    th { background: #f0f0f0; }
    .challan { border: 1px solid #000; padding: 15px; width: 100%; }
    .section { margin-bottom: 10px; }
    .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
    .work-description { min-height: 60px; }
</style>
</head>
<body>
@php($service = $ticket->services->first())

<div class="challan">

    <!-- Header -->
    <div class="header">
        <table style="border: none;">
            <tr>
                <!-- Left section (logo aligned right inside left block) -->
                <td style="width: 40%; text-align: right; vertical-align: middle; padding-right: 20px; border: none;">
                    <img src="{{ public_path('assets/img/logo/sbc-logo.webp') }}" alt="SBC Logo" style="width: 100px; height: auto;">
                </td>
                <!-- Right section (company text aligned left) -->
                <td style="width: 60%; text-align: left; vertical-align: middle; padding-left: 20px; border: none;">
                    <h1 style="margin: 0; font-size: 22px;">SBC COOLING PVT. LTD.</h1>
                    <p style="margin: 0; font-size: 14px;">Complete HVAC/R Solution</p>
                </td>
            </tr>
        </table>

        <!-- Address -->
        <div style="margin-top: 8px; text-align: center; font-size: 12px;">
            <p style="margin: 2px 0;">
                Unit 1: Plot No. 222-229, Part-1 &amp; Unit 2: Plot No. 90, Part-16, Om Textile Park, Post-Parab, Tal. Kamrej,
                N.H. No.8, Umbhel to Parab Road, Surat-394325, Gujarat, INDIA &nbsp; | &nbsp; Mo.: +91 98250 51800, +91 85111 48247
            </p>
        </div>

        <!-- Title -->
        <div style="margin-top: 10px; text-align: center;">
            <h2 style="margin: 0; font-size: 18px;">SERVICE CHALLAN</h2>
        </div>
    </div>

    <!-- Top details -->
    <div class="section">
        <table>
            <tr>
                <th>Date</th>
                <td>
                    @if($service && $service->start_date_time)
                        {{ $service->start_date_time->format('d/m/y') }}
                    @elseif($ticket->created_at)
                        {{ $ticket->created_at->format('d/m/y') }}
                    @else
                        -
                    @endif
                </td>
                <th>Time In</th>
                <td>{{ ($service && $service->start_date_time) ? $service->start_date_time->format('H:i') : '-' }}</td>
                <th>Time Out</th>
                <td>{{ ($service && $service->end_date_time) ? $service->end_date_time->format('H:i') : '-' }}</td>
                <th>Report Book No</th>
                <td>{{ $ticket->id }}</td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td>{{ $ticket->customer->name ?? '-' }}</td>
                <th>Unit Model No</th>
                <td>{{ $service->unit_model_number ?? ($ticket->orderProduct->product->model_no ?? '-') }}</td>
                <th>Unit SR No</th>
                <td>{{ $service->unit_sr_no ?? ($ticket->orderProduct->serial_number ?? '-') }}</td>
                <th>Mobile No</th>
                <td>{{ $ticket->customer->mobile ?? '-' }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td colspan="3">{{ $ticket->customer->address ?? '-' }}</td>
                <th>Contact Person</th>
                <td>{{ $service->contact_person_name ?? ($ticket->contactPerson->name ?? '-') }}</td>
                <th>Warranty/AMC/CAMC/Paid/Others</th>
                <td>{{ $service ? strtoupper($service->payment_type ?? '-') : '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Complaint / Work -->
    <div class="section">
        <table>
            <tr>
                <th>Customer Complaint</th>
                <td>{{ $ticket->subject ?? '-' }}</td>
            </tr>
            <tr>
                <th>Work Description</th>
                <td class="work-description">{{ $service->service_description ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Items -->
    <div class="section">
        <table>
            <tr>
                <th style="width: 55%;">Item</th>
                <th style="width: 15%;">Quantity</th>
                <th style="width: 15%;">Rate</th>
                <th style="width: 15%;">Amount</th>
            </tr>

            @php($itemsShown = false)
            @foreach($ticket->services as $svc)
                @foreach($svc->serviceItems as $it)
                    @php($itemsShown = true)
                    <tr>
                        <td>{{ $it->item }}</td>
                        <td>{{ $it->qty }}</td>
                        <td>{{ $it->rate }}</td>
                        <td>{{ $it->amount }}</td>
                    </tr>
                @endforeach
            @endforeach

            @if(!$itemsShown)
                <tr>
                    <td colspan="4" style="text-align:center;">No items</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Measurements -->
    <div class="section measurements">
        <table>
            <tr>
                <th>Refrigerant</th>
                <td>{{ $service->refrigerant ?? '-' }}</td>
                <th>Voltage</th>
                <td>{{ $service->voltage ?? '-' }}</td>
            </tr>
            <tr>
                <th>AMP-R</th>
                <td>{{ $service->amp_r ?? '-' }}</td>
                <th>AMP-Y</th>
                <td>{{ $service->amp_y ?? '-' }}</td>
            </tr>
            <tr>
                <th>AMP-B</th>
                <td>{{ $service->amp_b ?? '-' }}</td>
                <th>Standing Pressure</th>
                <td>{{ $service->standing_pressure ?? '-' }}</td>
            </tr>
            <tr>
                <th>Suction Pressure</th>
                <td>{{ $service->suction_pressure ?? '-' }}</td>
                <th>Discharge Pressure</th>
                <td>{{ $service->discharge_pressure ?? '-' }}</td>
            </tr>
            <tr>
                <th>Suction Temp</th>
                <td>{{ $service->suction_temp ?? '-' }}</td>
                <th>Discharge Temp</th>
                <td>{{ $service->discharge_temp ?? '-' }}</td>
            </tr>
            <tr>
                <th>EXV Opening %</th>
                <td>{{ $service->exv_opening ?? '-' }}</td>
                <th>Chilled Water In</th>
                <td>{{ $service->chilled_water_in ?? '-' }}</td>
            </tr>
            <tr>
                <th>Chilled Water Out</th>
                <td>{{ $service->chilled_water_out ?? '-' }}</td>
                <th>Condenser Water In</th>
                <td>{{ $service->con_water_in ?? '-' }}</td>
            </tr>
            <tr>
                <th>Condenser Water Out</th>
                <td>{{ $service->con_water_out ?? '-' }}</td>
                <th>Water Tank Temp</th>
                <td>{{ $service->water_tank_temp ?? '-' }}</td>
            </tr>
            <tr>
                <th>Cabinet Temp</th>
                <td>{{ $service->cabinet_temp ?? '-' }}</td>
                <th>Room Temp</th>
                <td>{{ $service->room_temp ?? '-' }}</td>
            </tr>
            <tr>
                <th>Room Supply Air Temp</th>
                <td>{{ $service->room_supply_air_temp ?? '-' }}</td>
                <th>Room Return Air Temp</th>
                <td>{{ $service->room_return_air_temp ?? '-' }}</td>
            </tr>
            <tr>
                <th>LP Settings</th>
                <td>{{ $service->lp_setting ?? '-' }}</td>
                <th>HP Settings</th>
                <td>{{ $service->hp_setting ?? '-' }}</td>
            </tr>
            <tr>
                <th>AFT</th>
                <td>{{ $service->aft ?? '-' }}</td>
                <th>Thermostat Setting</th>
                <td>{{ $service->thermostat_setting ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Signature -->
    <div style="display: flex; justify-content: flex-end; margin-top: 20px; text-align: right;">
        <div>
            <p style="margin: 0; font-weight: bold;">SBC Cooling Pvt. Ltd. Engineer Name &amp; Sign</p>
            <p style="margin: 2px 0;">{{ ($ticket->assignedTo->name ?? '-') }}</p>
            <img src="{{ public_path('storage/'.$ticket->assignedTo->sign_photo) }}" alt="Engineer Sign" style="max-width: 160px; height: auto; margin-top: 5px;">
        </div>
    </div>



</div>
</body>
</html>
