<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoriesService;
use App\Http\Requests\CategoriesStoreRequest;
use App\Http\Requests\CategoriesUpdateRequest;
use App\Models\ItemClassification;

class Categories extends Controller
{
    public function __construct(
        protected CategoriesService $categoriesService
    ) {}

    public function categories(Request $request)
    {
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $status = $request->input('status');

        return inertia('Categories', [
            'categories' => $this->categoriesService->filterAndPaginateCategories($search, $costRange, $status),
        ]);
    }

    public function store(CategoriesStoreRequest $request)
    {
        $this->categoriesService->createCategories(
            $request->validated()
        );

        return redirect()->back()->with('success', 'Categories added successfully.');
    }

    public function update(CategoriesUpdateRequest $request, $id)
    {
        $this->categoriesService->updateCategories($id, $request->validated());

        return redirect()->back()->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $supplier = ItemClassification::findOrFail($id);
        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier deleted successfully.');
    }
}
