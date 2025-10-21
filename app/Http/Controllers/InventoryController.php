<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
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
    public function searchBar(Request $request, InventoryService $service){
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $itemClassifications = ItemClassification::all();
        $suppliers = Supplier::all();
        $locations = Location::all();
        $invoices = Invoice::all();
        $fundSources = FundSource::all();
        
        return Inertia::render('Inventory', [
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
            'property_id' => 'required|integer',
            'item_classification_id' => 'required|integer',
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric',
            'total_amount' => 'nullable|numeric',
            'status' => 'nullable|string|max:50',
        ]);

        // Calculate the total amount if necessary
        $totalAmount = $request->quantity * $request->unit_cost;

        // Create a new InventoryItem using mass assignment
        InventoryItem::create([
            'property_id' => $request->property_id,
            'item_classification_id' => $request->item_classification_id,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'unit_cost' => $request->unit_cost,
            'total_amount' => $totalAmount, // You may want to calculate this dynamically
            'status' => $request->status,
        ]);

        // Redirect back or to a specific page after successful submission
        return redirect()->route('inventory.index');
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

}
