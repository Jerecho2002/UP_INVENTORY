<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintController extends Controller
{
    public function printReceipt(Request $request)
    {
        // 1. Fetch data (example)
        $data = [
            'title' => 'Sample Receipt',
            'date'  => now()->format('Y-m-d'),
        ];

        // 2. Generate PDF from Blade view
        $pdf = Pdf::loadView('prints.receipt', $data);

        // 3. Create filename
        $fileName = 'receipt_' . time() . '.pdf';

        // 4. Store PDF in public storage
        Storage::disk('public')->put(
            'prints/' . $fileName,
            $pdf->output()
        );

        // 5. Generate public URL
        $fileUrl = asset('storage/prints/' . $fileName);

        // 6. Return URL for Inertia / frontend
        return response()->json([
            'success' => true,
            'url'     => $fileUrl,
        ]);
    }
}
