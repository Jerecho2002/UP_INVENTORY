<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoriesService;
use App\Http\Requests\CategoriesStoreRequest;

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
}
