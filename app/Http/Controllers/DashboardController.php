<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService,
    ){}

    public function searchBar(Request $request){
        $search = $request->input('search');
        return Inertia::render('Dashboard', [
        'items' => $this->dashboardService->filterAndPaginateInventory($search),
        'searchItem' => $search,
    ]);
    }
}
