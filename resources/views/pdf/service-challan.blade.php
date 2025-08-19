<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Service Challan</title>
<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin: 10px;
        color: #000;
    }
    table {
        width: 100%;
        table-layout: fixed;
        word-wrap: break-word;
        font-size: 12px;
    }
    .challan {
        border: 1px solid #000;
        padding: 15px;
        width: 100%; /* ✅ changed from 800px to 100% */
        box-sizing: border-box;
    }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
    .header img { height: 50px; vertical-align: middle; }
    .header h1, .header p { margin: 0; }
    .section { margin-bottom: 10px; }
    .section table { width: 100%; border-collapse: collapse; }
    .section table td, .section table th { border: 1px solid #000; padding: 5px; vertical-align: top; }
    .section table th { background-color: #f0f0f0; text-align: left; }
    .work-description { min-height: 60px; }
    .measurements td { width: 25%; }
    .signature { margin-top: 20px; }
    .signature div { display: inline-block; width: 48%; text-align: center; vertical-align: top; }
</style>
</head>
<body>

<div class="challan">
    <div class="header">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
                <!-- Left section (logo aligned right inside left block) -->
                <td style="width: 40%; text-align: right; vertical-align: middle; padding-right: 20px;">
                    <img src="{{ public_path('assets/img/logo/sbc-logo.webp') }}" 
                        alt="SBC Logo" 
                        style="width: 100px; height: auto;">
                </td>

                <!-- Right section (company text aligned left) -->
                <td style="width: 60%; text-align: left; vertical-align: middle; padding-left: 20px;">
                    <h1 style="margin: 0; font-size: 22px;">SBC COOLING PVT. LTD.</h1>
                    <p style="margin: 0; font-size: 14px;">Complete HVAC/R Solution</p>
                </td>
            </tr>
        </table>

        <!-- Address Section -->
        <div style="margin-top: 8px; text-align: center; font-size: 12px;">
            <p style="margin: 2px 0;">Unit 1: Plot No. 222-229, Part-1 & Unit 2: Plot No. 90, Part-16, Om Textile Park, Post-Parab, Tal. Kamrej, N.H. No.8, Umbhel to Parab Road, Surat-394325, Gujarat, INDIA Mo.: +91 98250 51800, +91 85111 48247</p>
            
        </div>

        <!-- Service Challan Title -->
        <div style="margin-top: 10px; text-align: center;">
            <h2 style="margin: 0; font-size: 18px;">SERVICE CHALLAN</h2>
        </div>
    </div>

    {{-- Sample dynamic data --}}
    <div class="section">
        <table>
            <tr>
                <th>Date</th>
                <td>{{ $data['date'] ?? '03/04/25' }}</td>
                <th>Time In</th>
                <td>{{ $data['time_in'] ?? '12:30' }}</td>
                <th>Time Out</th>
                <td>{{ $data['time_out'] ?? '15:00' }}</td>
                <th>Report Book No</th>
                <td>{{ $data['report_no'] ?? '1307' }}</td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td>{{ $data['customer'] ?? 'J K Bakery' }}</td>
                <th>Unit Model No</th>
                <td>{{ $data['model_no'] ?? 'CNXBDCPS 2500' }}</td>
                <th>Unit SR No</th>
                <td>{{ $data['sr_no'] ?? 'SBC-22241116' }}</td>
                <th>Mobile No</th>
                <td>{{ $data['mobile'] ?? '7779007888' }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td colspan="3">{{ $data['address'] ?? 'Bazar Industrial Area' }}</td>
                <th>Contact Person</th>
                <td>{{ $data['contact'] ?? 'Hasmukh Sir' }}</td>
                <th>Warranty/AMC/CAMC/Paid/Others</th>
                <td>{{ $data['type'] ?? 'Paid' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <th>Customer Complaint</th>
                <td>{{ $data['complaint'] ?? 'Air Double System commissioning' }}</td>
            </tr>
            <tr>
                <th>Work Description</th>
                <td class="work-description">
                    {{ $data['work'] ?? 'Double system Air cool chiller running but 1 system is continuing on & parameters. Both system set.' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>{{ $data['item'] ?? 'Plus 7 DG Hand over' }}</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
        </table>
    </div>

    <div class="section measurements">
        <table border="1" cellspacing="0" cellpadding="6" style="width:100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <tr>
                <th>Refrigerant</th>
                <td>R22</td>
                <th>Voltage</th>
                <td>412</td>
            </tr>
            <tr>
                <th>AMP-R</th>
                <td>78</td>
                <th>AMP-Y</th>
                <td>77</td>
            </tr>
            <tr>
                <th>AMP-B</th>
                <td>77</td>
                <th>Standing Pressure</th>
                <td>140</td>
            </tr>
            <tr>
                <th>Suction Pressure</th>
                <td>71.2</td>
                <th>Discharge Pressure</th>
                <td>302</td>
            </tr>
            <tr>
                <th>Suction Temp</th>
                <td>12</td>
                <th>Discharge Temp</th>
                <td>16.2</td>
            </tr>
            <tr>
                <th>EXV Opening %</th>
                <td>--</td>
                <th>Chilled Water In</th>
                <td>--</td>
            </tr>
            <tr>
                <th>Chilled Water Out</th>
                <td>--</td>
                <th>Condenser Water In</th>
                <td>--</td>
            </tr>
            <tr>
                <th>Condenser Water Out</th>
                <td>--</td>
                <th>Water Tank Temp</th>
                <td>--</td>
            </tr>
            <tr>
                <th>Cabinet Temp</th>
                <td>--</td>
                <th>Room Temp</th>
                <td>--</td>
            </tr>
            <tr>
                <th>Room Supply Air Temp</th>
                <td>--</td>
                <th>Room Return Air Temp</th>
                <td>--</td>
            </tr>
            <tr>
                <th>LP Settings</th>
                <td>--</td>
                <th>HP Settings</th>
                <td>--</td>
            </tr>
            <tr>
                <th>AFT Thermostat Setting</th>
                <td>--</td>
                <th></th>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="signature" style="display: flex; justify-content: flex-end; margin-top: 40px; text-align: right;">
        <div>
            <p style="margin: 0; font-weight: bold;">SBC Cooling Pvt. Ltd. Engineer Name &amp; Sign</p>
            <p style="margin: 2px 0;">Staff 1</p>
            <img src="{{ public_path('assets/img/logo/sbc-logo.webp')  }}" alt="Engineer Sign" style="max-width: 160px; height: auto; margin-top: 5px;">
        </div>
    </div>
</div>

</body>
</html>
