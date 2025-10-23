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

class InventoryController extends Controller
{
    public function InventoryItems(Request $request, InventoryService $service){
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $itemClassifications = ItemClassification::all();
        $suppliers = Supplier::all();
        $locations = Location::all();
        $invoices = Invoice::all();
        $fundSources = FundSource::all();
        
        return inertia('Inventory/InventoryItem', [
        'items' => $service->getPaginatedInventory($search, $costRange),
        'itemClassifications' => $itemClassifications,
        'suppliers' => $suppliers,
        'locations' => $locations,
        'invoices' => $invoices,
        'fundSources' => $fundSources,
    ]);
    }

    public function InventoryTransactions(Request $request, InventoryService $service){
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $itemClassifications = ItemClassification::all();
        $suppliers = Supplier::all();
        $locations = Location::all();
        $invoices = Invoice::all();
        $fundSources = FundSource::all();
        
        return inertia('Inventory/InventoryTransaction', [
        'items' => $service->getPaginatedInventory($search, $costRange),
        'itemClassifications' => $itemClassifications,
        'suppliers' => $suppliers,
        'locations' => $locations,
        'invoices' => $invoices,
        'fundSources' => $fundSources,
    ]);
    }
    
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'item_classification_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'location_id' => 'required|integer',
            'invoice_id' => 'required|integer',
            'fund_source_id' => 'required|integer',
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
            'status' => 'nullable|string|max:50',
        ]);

        // Calculate the total amount if necessary
        $totalAmount = $request->quantity * $request->unit_cost;

        // Create a new InventoryItem using mass assignment
        InventoryItem::create([
            'item_classification_id' => $request->item_classification_id,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'invoice_id' => $request->invoice_id,
            'fund_source_id' => $request->fund_source_id,
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

    public function update(Request $request, $id)
    {
        // Validate incoming request
        $request->validate([
            'item_classification_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'location_id' => 'required|integer',
            'invoice_id' => 'required|integer',
            'fund_source_id' => 'required|integer',
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
            'location_id' => $request->location_id,
            'invoice_id' => $request->invoice_id,
            'fund_source_id' => $request->fund_source_id,
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
