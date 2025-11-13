<?php

namespace App\Http\Controllers\API;

use App\Models\Invoice;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\FundSource;
use Illuminate\Http\Request;
use App\Models\ItemClassification;
use App\Services\InventoryService;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function index(Request $request, InventoryService $service)
    {
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $status = $request->input('status');
        $itemClassifications = ItemClassification::all();
        $suppliers = Supplier::all();

        return response()->json([
            'items' => $service->getPaginatedInventory($search, $costRange, $status),
            'itemClassifications' => $itemClassifications,
            'suppliers' => $suppliers,
        ]);
    }
}
