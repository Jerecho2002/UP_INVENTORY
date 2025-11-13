<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\FundSource;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\ItemClassification;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Http;
use App\Services\InventoryTransactionService;

class InventoryController extends Controller
{
    public function InventoryItems(Request $request, InventoryService $service)
    {
        $response = Http::get('http://127.0.0.1:8001/api/rooms');
        $rooms = $response->json('rooms');

        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $status = $request->input('status');
        $itemClassifications = ItemClassification::all();
        $suppliers = Supplier::all();

        return inertia('Inventory/InventoryItem', [
            'rooms' => $rooms,
            'items' => $service->getPaginatedInventory($search, $costRange, $status),
            'itemClassifications' => $itemClassifications,
            'suppliers' => $suppliers,
        ]);
    }

    public function InventoryTransactions(Request $request, InventoryTransactionService $service)
    {
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $status = $request->input('status');

        return inertia('Inventory/InventoryTransaction', [
            'items' => $service->getPaginatedInventory($search, $costRange, $status),
        ]);
    }

    public function InventoryAcknowledgements(Request $request, InventoryService $service)
    {
        $search = $request->input(key: 'search');
        $costRange = $request->input('cost_range');
        $itemClassifications = ItemClassification::all();
        $suppliers = Supplier::all();

        return inertia('Inventory/InventoryAcknowledgements', [
            'items' => $service->getPaginatedInventory($search, $costRange),
            'itemClassifications' => $itemClassifications,
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_classification_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'room_name' => 'required|string|max:50',
            'invoice' => 'required|string|max:50',
            'fund_source' => 'required|string|max:50',
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric',
            'serial_numbers' => 'required|array|min:1',
            'serial_numbers.*' => 'required|string|max:50',
            'property_number' => 'required|string|max:50',
            'pr_number' => 'required|string|max:50',
            'po_number' => 'required|string|max:50',
            'remarks' => 'required|string|max:50',
            'date_acquired' => 'required|date',
            'status' => 'nullable|string|max:50',
        ]);

        foreach ($request->serial_numbers as $index => $serialNumber) {
            $propertyNumber = $request->property_number . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

            InventoryItem::create([
                'item_classification_id' => $request->item_classification_id,
                'supplier_id' => $request->supplier_id,
                'room_name' => $request->room_name,
                'invoice' => $request->invoice,
                'fund_source' => $request->fund_source,
                'item_name' => $request->item_name,
                'description' => $request->description,
                'category' => $request->category,
                'quantity' => 1, // per record
                'unit' => $request->unit,
                'unit_cost' => $request->unit_cost,
                'total_amount' => $request->unit_cost,
                'property_number' => $propertyNumber,
                'serial_number' => $serialNumber, // <- single column
                'pr_number' => $request->pr_number,
                'po_number' => $request->po_number,
                'remarks' => $request->remarks,
                'date_acquired' => $request->date_acquired,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('inventory.items');
    }


    public function update(Request $request, $id)
    {
        // Validate incoming request
        $request->validate([
            'item_classification_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'room_name' => 'required|string|max:50',
            'invoice' => 'required|string|max:50',
            'fund_source' => 'required|string|max:50',
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric',
            'total_amount' => 'nullable|numeric',
            'property_number' => 'required|string|max:50',
            'serial_number' => 'required|string|max:50',
            'pr_number' => 'required|string|max:50',
            'po_number' => 'required|string|max:50',
            'remarks' => 'required|string|max:50',
            'date_acquired' => 'required|date',
            'status' => 'nullable|integer',
        ]);

        $item = InventoryItem::findOrFail($id);
        // Calculate the total amount if necessary
        $totalAmount = $request->quantity * $request->unit_cost;

        // Create a new InventoryItem using mass assignment
        $item->update([
            'item_classification_id' => $request->item_classification_id,
            'supplier_id' => $request->supplier_id,
            'room_name' => $request->room_name,
            'invoice' => $request->invoice,
            'fund_source' => $request->fund_source,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'unit_cost' => $request->unit_cost,
            'total_amount' => $totalAmount,
            'property_number' => $request->property_number,
            'serial_number' => $request->serial_number,
            'pr_number' => $request->pr_number,
            'po_number' => $request->po_number,
            'remarks' => $request->remarks,
            'date_acquired' => $request->date_acquired,
            'status' => $request->status,
        ]);

        // Redirect back or to a specific page after successful submission
        return redirect()->route('inventory.items');
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

}
