<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\PdfExportService;
use App\Services\NotificationService;

class ChallanController extends Controller
{
    protected $pdfExportService;
    protected $notificationService;

    public function __construct(PdfExportService $pdfExportService, NotificationService $notificationService)
    {
        $this->pdfExportService = $pdfExportService;
        $this->notificationService = $notificationService;
    }

    /**
     * Export single ticket to PDF
     */
    public function exportServiceChallan($id)
    {
        /*
        // Handle both UUID and ID parameters
        if (is_numeric($id)) {
            $ticket = Ticket::with([
                'customer', 
                'assignedTo', 
                'attendedBy', 
                'contactPerson',
                'orderProduct.product',
                'orderProduct.order',
                'additionalStaff',
                'services',
                'ticketImages.uploadedBy'
            ])->findOrFail($id);
        } else {
            $ticket = Ticket::with([
                'customer', 
                'assignedTo', 
                'attendedBy', 
                'contactPerson',
                'orderProduct.product',
                'orderProduct.order',
                'additionalStaff',
                'services',
                'ticketImages.uploadedBy'
            ])->where('uuid', $id)->firstOrFail();
        }

        // Debug: Log what we're loading
        Log::info('PDF Export Debug', [
            'ticket_id' => $ticket->id,
            'ticket_uuid' => $ticket->uuid,
            'ticket_subject' => $ticket->subject,
            'customer_loaded' => $ticket->customer ? 'YES' : 'NO',
            'services_count' => $ticket->services->count(),
            'images_count' => $ticket->ticketImages->count(),
        ]);
        
        return $this->pdfExportService->generateSingleTicketPdf($ticket);
        */

        $data = [
            'date' => '03/04/25',
            'time_in' => '12:30',
            'time_out' => '15:00',
            'report_no' => '1307',
            'customer' => 'J K Bakery',
            'model_no' => 'CNXBDCPS 2500',
            'sr_no' => 'SBC-22241116',
            'mobile' => '7779007888',
            'address' => 'Bazar Industrial Area',
            'contact' => 'Hasmukh Sir',
            'type' => 'Paid',
            'complaint' => 'Air Double System commissioning',
            'work' => 'Double system Air cool chiller running but 1 system is continuing on & parameters. Both system set.',
            'item' => 'Plus 7 DG Hand over',
            'refrigerant' => 'R22',
            'voltage' => 412,
            'amp_r' => 78,
            'amp_y' => 77,
            'amp_b' => 77,
            'standing_pressure' => 140,
            'suction_pressure' => 71.2,
            'discharge_pressure' => 302,
            'suction_temp' => 12,
            'discharge_temp' => 16.2,
            'exv_opening' => null,
            'chilled_water_in' => null,
            'chilled_water_out' => null,
            'condenser_water_in' => null,
            'condenser_water_out' => null,
            'water_tank_temp' => null,
            'cabinet_temp' => null,
            'room_temp' => null,
            'room_supply_air_temp' => null,
            'room_return_air_temp' => null,
            'lp_settings' => null,
            'hp_settings' => null,
            'aft_thermostat_setting' => null,
        ];


        return $this->pdfExportService->generateServiceChallanPdf($data);
    }

    public function previewServiceChallan()
    {
        $data = [
            'date' => '03/04/25',
            'time_in' => '12:30',
            'time_out' => '15:00',
            'report_no' => '1307',
            'customer' => 'J K Bakery',
            'model_no' => 'CNXBDCPS 2500',
            'sr_no' => 'SBC-22241116',
            'mobile' => '7779007888',
            'address' => 'Bazar Industrial Area',
            'contact' => 'Hasmukh Sir',
            'type' => 'Paid',
            'complaint' => 'Air Double System commissioning',
            'work' => 'Double system Air cool chiller running but 1 system is continuing on & parameters. Both system set.',
            'item' => 'Plus 7 DG Hand over',
            'refrigerant' => 'R22',
            'voltage' => 412,
            'amp_r' => 78,
            'amp_y' => 77,
            'amp_b' => 77,
            'standing_pressure' => 140,
            'suction_pressure' => 71.2,
            'discharge_pressure' => 302,
            'suction_temp' => 12,
            'discharge_temp' => 16.2,
            'exv_opening' => null,
            'chilled_water_in' => null,
            'chilled_water_out' => null,
            'condenser_water_in' => null,
            'condenser_water_out' => null,
            'water_tank_temp' => null,
            'cabinet_temp' => null,
            'room_temp' => null,
            'room_supply_air_temp' => null,
            'room_return_air_temp' => null,
            'lp_settings' => null,
            'hp_settings' => null,
            'aft_thermostat_setting' => null,
        ];

        // ✅ Instead of PDF, just return the Blade view
        return view('pdf.service-challan', ['data' => $data]);
    }
   
}
