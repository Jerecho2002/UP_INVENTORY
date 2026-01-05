<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PrintService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AcknowledgementItem;
use Illuminate\Support\Facades\Storage;

class PrintController extends Controller
{
    public function __construct(
        protected PrintService $printService,
    ){}
     public function printReceipt(Request $request)
    {
        try {
            $ids = $request->input('ids');

            $url = $this->printService->generateReceiptPdf($ids);

            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
