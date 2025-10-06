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
        $status = $request->input('status');
        $from = $request->input('from');
        $to = $request->input('to');

        return Inertia::render('Reports', [
            'items' => $service->getPaginatedInventory($search, $status, $from, $to),
            'searchItem' => $search,
            'selectedStatus' => $status,
            'fromDate' => $from,
            'toDate' => $to,
        ]);
    }

}
