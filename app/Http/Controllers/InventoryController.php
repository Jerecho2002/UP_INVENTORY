<?php

namespace App\Http\Controllers;

use App\Models\AccountablePerson;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Services\RoomApiService;
use App\Models\ItemClassification;
use App\Services\InventoryService;
use App\Http\Requests\InventoryStoreRequest;
use App\Services\AcknowledgementItemService;
use App\Http\Requests\InventoryUpdateRequest;
use App\Services\AcknowledgementReceiptService;
use App\Http\Requests\UpdateInventoryCategoryRequest;
use App\Http\Requests\InventoryAcknowledgementStoreRequest;

class InventoryController extends Controller
{
    public function InventoryItems(Request $request, InventoryService $service, RoomApiService $roomsApi)
    {
        // $rooms = $roomsApi->fetchRooms();

        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $status = $request->input('status');
        $itemClassifications = ItemClassification::all();
        $suppliers = Supplier::all();

        return inertia('Inventory/InventoryItem', [
            // 'rooms' => $rooms,
            'items' => $service->filterAndPaginateInventory($search, $costRange, $status),
            'itemClassifications' => $itemClassifications,
            'suppliers' => $suppliers,
        ]);
    }

    public function InventoryTransactions(Request $request, InventoryService $service)
    {
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $status = $request->input('status');

        return inertia('Inventory/InventoryTransaction', [
            'items' => $service->filterAndPaginateTransaction($search, $costRange, $status),
        ]);
    }

    public function InventoryAcknowledgements(Request $request, AcknowledgementReceiptService $service)
    {
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $users = User::all();
        $accPerson = AccountablePerson::all();

        return inertia('Inventory/InventoryAcknowledgements', [
            'items' => $service->filterAndPaginateAcknowledgementReceipt($search, $costRange),
            'accPerson' => $accPerson,
            'users' => $users,
        ]);
    }

    public function InventoryAcknowledgementsStore(InventoryAcknowledgementStoreRequest $request, AcknowledgementReceiptService $service)
    {
        $service->createAcknowledgements($request->validated());

        return redirect()->route('inventory.acknowledgements')->with('success', 'Items assigned successfully!');
    }

    public function updateCategoryForItems(UpdateInventoryCategoryRequest $request, InventoryService $service)
    {
        $service->updateCategory($request->input('inventory_item_ids'),
        $request->input('category'));

        return back()->with('success', 'Category updated for selected items.');
    }


    public function store(InventoryStoreRequest $request, InventoryService $service)
    {
        $service->createinventoryItems($request->validated());

        return redirect()->route('inventory.items');
    }


    public function update(InventoryUpdateRequest $request, $id, InventoryService $service)
    {
        $service->updateInventoryItem($id, $request->validated());

        return redirect()->route('inventory.items');
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    public function convert(Request $request, InventoryService $service)
    {
        return $service->convertToCsv($request);
    }

    public function importCsv(Request $request, InventoryService $service)
    {
        return $service->importCsv($request);
    }

    public function exportCsv(InventoryService $service)
    {
        return $service->exportCsv();
    }
}
