<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class SimplePdfExportService
{
    /**
     * Generate a simple HTML-based PDF alternative
     */
    public function generatePdf($view, $data = [], $filename = 'export.pdf')
    {
        try {
            // Generate HTML content
            $html = View::make($view, $data)->render();
            
            // Create a styled HTML version that looks like a PDF
            $styledHtml = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>PDF Export</title>
                <style>
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                    body { 
                        font-family: Arial, sans-serif; 
                        margin: 20px; 
                        line-height: 1.6;
                    }
                    .pdf-header {
                        text-align: center;
                        margin-bottom: 20px;
                        border-bottom: 2px solid #ccc;
                        padding-bottom: 10px;
                    }
                    .pdf-content {
                        margin-top: 20px;
                    }
                    .print-btn {
                        background: #007bff;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                        margin-bottom: 20px;
                    }
                    .print-btn:hover {
                        background: #0056b3;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    .logo {
                        max-width: 200px;
                        height: auto;
                    }
                </style>
                <script>
                    function printPage() {
                        window.print();
                    }
                    
                    // Auto-trigger print dialog
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                        }, 1000);
                    }
                </script>
            </head>
            <body>
                <div class="no-print">
                    <button class="print-btn" onclick="printPage()">Print / Save as PDF</button>
                    <p><em>Note: DomPDF is not available. This is a printable HTML version. Use your browser\'s Print function to save as PDF.</em></p>
                </div>
                
                <div class="pdf-header">
                    <h1>Export Report</h1>
                    <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
                </div>
                
                <div class="pdf-content">
                    ' . $html . '
                </div>
            </body>
            </html>';
            
            return Response::make($styledHtml, 200, [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'inline; filename="' . $filename . '.html"'
            ]);
            
        } catch (\Exception $e) {
            // Final fallback
            return Response::make('Error generating PDF: ' . $e->getMessage(), 500);
        }
    }
}
