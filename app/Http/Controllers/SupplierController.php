<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupplierService;

class SupplierController extends Controller
{
    public function index(){
        return inertia('Suppliers');
    }

    public function suppliers(Request $request, SupplierService $service){
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $status = $request->input('status');

        return inertia('Suppliers', [
            'suppliers' => $service->filterAndPaginateSuppliers($search, $costRange, $status),
        ]);
    }
}
