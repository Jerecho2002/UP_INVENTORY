<?php

namespace App\Http\Controllers;

use App\Models\AcknowledgementItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function printReceipt(Request $request)
    {
        // 1. Get selected IDs from Vue
        $ids = $request->input('ids', []);

        // Safety check
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected'
            ], 422);
        }

        // 2. Fetch ONLY selected records
        $acknowledgementItems = AcknowledgementItem::with([
            'inventoryItems',
            'acknowledgementReceipts.accountablePerson',
            'acknowledgementReceipts.issuedBy'
        ])
        ->whereIn('id', $ids)
        ->get();

        // 3. Generate PDF
        $pdf = Pdf::loadView('prints.receipt', [
            'acknowledgementItems' => $acknowledgementItems,
        ]);

        // 4. Store PDF
        $fileName = 'receipt_' . time() . '.pdf';
        Storage::disk('public')->put(
            'prints/' . $fileName,
            $pdf->output()
        );

        // 5. Return public URL
        return response()->json([
            'success' => true,
            'url' => asset('storage/prints/' . $fileName),
        ]);
    }
}
