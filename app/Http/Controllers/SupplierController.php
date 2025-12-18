<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Services\SupplierService;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $supplierService
    ) {}

    public function suppliers(Request $request)
    {
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $status = $request->input('status');

        return inertia('Suppliers', [
            'suppliers' => $this->supplierService->filterAndPaginateSuppliers($search, $costRange, $status),
        ]);
    }

    public function store(SupplierRequest $request)
    {
        $this->supplierService->createSupplier(
            $request->validated()
        );

        return redirect()->back()->with('success', 'Supplier added successfully.');
    }
}
