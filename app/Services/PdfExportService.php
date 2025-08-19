<?php

namespace App\Services;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use PDF;

class PdfExportService
{
    /**
     * Generate PDF from view with data
     */
    public function generatePdf($view, $data = [], $filename = 'export.pdf')
    {
        try {
            // Try to create PDF using facade
            $html = View::make($view, $data)->render();
            $pdf = PDF::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            // If DomPDF fails, use HTML fallback
            return $this->generateHtmlFallback($view, $data, $filename);
        }
    }
    
    /**
     * Generate HTML fallback when PDF fails
     */
    private function generateHtmlFallback($view, $data, $filename)
    {
        $html = View::make($view, $data)->render();
        
        $htmlContent = '<!DOCTYPE html>
            <html>
            <head>
                <title>PDF Export</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .warning { 
                        background-color: #fff3cd; 
                        color: #856404; 
                        padding: 15px; 
                        border: 1px solid #ffeaa7; 
                        border-radius: 5px; 
                        margin-bottom: 20px; 
                    }
                </style>
            </head>
            <body>
                <div class="warning">
                    <strong>Notice:</strong> PDF generation is not fully configured. This is the HTML version of your report.
                    <br><em>To enable PDF downloads, please install and configure DomPDF properly.</em>
                </div>
                ' . $html . '
            </body>
            </html>';
            
        return Response::make($htmlContent, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"',
        ]);
    }
    
    /**
     * Generate Users PDF
     */
    public function generateUsersPdf($users, $filters = [])
    {
        $data = [
            'users' => $users,
            'filters' => $filters,
            'title' => 'Users Report',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.users', $data, 'users-report.pdf');
    }
    
    /**
     * Generate Orders PDF
     */
    public function generateOrdersPdf($orders, $filters = [])
    {
        $data = [
            'orders' => $orders,
            'filters' => $filters,
            'title' => 'Orders Report',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.orders', $data, 'orders-report.pdf');
    }
    
    /**
     * Generate Customers PDF
     */
    public function generateCustomersPdf($customers, $filters = [])
    {
        $data = [
            'customers' => $customers,
            'filters' => $filters,
            'title' => 'Customers Report',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.customers', $data, 'customers-report.pdf');
    }
    
    /**
     * Generate Products PDF
     */
    public function generateProductsPdf($products, $filters = [])
    {
        $data = [
            'products' => $products,
            'filters' => $filters,
            'title' => 'Products Report',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.products', $data, 'products-report.pdf');
    }
    
    /**
     * Generate Staff PDF
     */
    public function generateStaffPdf($staff, $filters = [])
    {
        $data = [
            'staff' => $staff,
            'filters' => $filters,
            'title' => 'Staff Report',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.staff', $data, 'staff-report.pdf');
    }
    
    /**
     * Generate Staff Attendance PDF
     */
    public function generateStaffAttendancePdf($attendanceData, $filters = [])
    {
        $data = [
            'attendanceData' => $attendanceData,
            'filters' => $filters,
            'title' => 'Staff Attendance Report',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.staff-attendance', $data, 'staff-attendance-report.pdf');
    }
    
    /**
     * Generate Tickets PDF
     */
    public function generateTicketsPdf($tickets, $filters = [])
    {
        $data = [
            'tickets' => $tickets,
            'filters' => $filters,
            'title' => 'Tickets Report',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.tickets', $data, 'tickets-report.pdf');
    }
    
    /**
     * Generate Single Order PDF
     */
    public function generateSingleOrderPdf($order)
    {
        $data = [
            'order' => $order,
            'title' => 'Order Details',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.single-order', $data, 'order-' . $order->id . '.pdf');
    }
    
    /**
     * Generate Single Ticket PDF
     */
    public function generateSingleTicketPdf($ticket)
    {
        $data = [
            'ticket' => $ticket,
            'title' => 'Ticket Details',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.single-ticket', $data, 'ticket-' . $ticket->id . '.pdf');
    }

    /**
     * Generate Single Customer PDF
     */
    public function generateSingleCustomerPdf($customer)
    {
        $data = [
            'customer' => $customer,
            'title' => 'Customer Details',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.single-customer', $data, 'customer-' . $customer->id . '.pdf');
    }

    /**
     * Generate Single Product PDF
     */
    public function generateSingleProductPdf($product)
    {
        $data = [
            'product' => $product,
            'title' => 'Product Details',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.single-product', $data, 'product-' . $product->id . '.pdf');
    }

    /**
     * Generate Single User PDF
     */
    public function generateSingleUserPdf($user)
    {
        $data = [
            'user' => $user,
            'title' => 'User Details',
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->generatePdf('pdf.single-user', $data, 'user-' . $user->id . '.pdf');
    }

    public function generateServiceChallanPdf($data)
    {
        //return $this->generatePdf('pdf.service-challan', ['data' => $data], 'service-challan.pdf');
        try {
            $html = \View::make('pdf.service-challan', ['data' => $data])->render();

            $pdf = \PDF::loadHTML($html);
            $pdf->setPaper('A4', 'portrait'); // ✅ fix cut issue by using landscape

            return $pdf->download('service-challan.pdf');
        } catch (\Exception $e) {
            // fallback to HTML download if PDF fails
            return $this->generateHtmlFallback('pdf.service-challan', ['data' => $data], 'service-challan.pdf');
        }
    }
}
