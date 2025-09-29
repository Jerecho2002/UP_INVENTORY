<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\InventoryService;

class InventoryController extends Controller
{
    public function searchBar(Request $request, InventoryService $service){
        return Inertia::render('Inventory', [
        'items' => $service->getPaginatedInventory($request->search),
        'searchItem' => $request->search
    ]);
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

}
