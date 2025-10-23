<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function searchBar(Request $request, ReportService $service)
    {
        $search = $request->input('search');
        $quantity = $request->input('quantity');
        $from = $request->input('from');
        $to = $request->input('to');

        return Inertia::render('Reports', [
            'items' => $service->getPaginatedInventory($search, $quantity, $from, $to),
            'searchItem' => $search,
            'selectedStatus' => $quantity,
            'fromDate' => $from,
            'toDate' => $to,
        ]);
    }

}
