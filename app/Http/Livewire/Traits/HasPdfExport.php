<?php

namespace App\Http\Livewire\Traits;

use App\Services\PdfExportService;
use Illuminate\Support\Collection;

trait HasPdfExport
{
    protected $pdfExportService;

    public function boot()
    {
        $this->pdfExportService = resolve(PdfExportService::class);
    }

    public function exportPdf()
    {
        $filters = $this->getFilters();
        $data = $this->getDataForPdf();
        
        return $this->generatePdf($data, $filters);
    }

    protected function getFilters()
    {
        return [];
    }

    protected function getDataForPdf()
    {
        return new Collection();
    }

    protected function generatePdf($data, $filters)
    {
        // This method should be implemented by the component
        return null;
    }
}
