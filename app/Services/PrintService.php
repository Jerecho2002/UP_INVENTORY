<?php

namespace App\Services;

use App\Models\AcknowledgementItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PrintService
{
    public function generateReceiptPdf(int|array $ids)
    {
        // Normalize IDs
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $ids = array_filter($ids, fn($id) => !is_null($id) && $id !== '');
        $ids = array_map('intval', $ids);

        if (empty($ids)) {
            throw new \Exception('No valid ID provided');
        }

        $acknowledgementItems = AcknowledgementItem::with([
            'inventoryItems',
            'acknowledgementReceipts.accountablePerson',
            'acknowledgementReceipts.issuedBy'
        ])->whereIn('id', $ids)->get();

        if ($acknowledgementItems->isEmpty()) {
            throw new \Exception('Item(s) not found');
        }

        // Generate PDF
        $pdf = Pdf::loadView('prints.receipt', [
            'acknowledgementItems' => $acknowledgementItems,
        ]);

        return $pdf; // return the PDF instance, let controller stream it
    }
}
