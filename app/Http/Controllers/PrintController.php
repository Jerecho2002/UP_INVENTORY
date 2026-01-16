<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PrintService;

class PrintController extends Controller
{
    public function __construct(
        protected PrintService $printService,
    ) {}
    public function printReceipt(Request $request)
    {
        $ids = $request->input('ids');

        $pdf = $this->printService->generateReceiptPdf($ids);

        // Force the browser to download the PDF instead of opening it
        $fileName = 'receipt_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($fileName);
    }
}
