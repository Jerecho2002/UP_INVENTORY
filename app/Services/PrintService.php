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

<<<<<<< HEAD
        return $pdf; // return the PDF instance, let controller stream it
=======
        $pdf->setPaper('A4', 'portrait');

        // Store PDF
        $fileName = 'receipt_' . time() . '.pdf';
        Storage::disk('public')->put('prints/' . $fileName, $pdf->output());

        // Cleanup old PDFs (keep latest 5 including this new one)
        $this->cleanupOldFilesByCount(5);

        return asset('storage/prints/' . $fileName);
    }


    protected function cleanupOldFilesByCount(int $maxFiles = 5): void
    {
        $path = storage_path('app/public/prints');
        if (!File::exists($path))
            return;

        // Get all files and sort by creation time (newest first)
        $files = collect(File::files($path))
            ->sortByDesc(fn($file) => $file->getCTime());

        // Keep only the latest $maxFiles, delete the rest
        $filesToDelete = $files->slice($maxFiles);

        foreach ($filesToDelete as $file) {
            File::delete($file->getRealPath());
        }
>>>>>>> modifier
    }
}
