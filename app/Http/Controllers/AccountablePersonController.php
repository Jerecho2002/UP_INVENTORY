<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountablePersonService;
use App\Http\Requests\AccountablePersonStoreRequest;

class AccountablePersonController extends Controller
{
    public function __construct(
        protected AccountablePersonService $accountablePersonService
    ) {}

    public function accountablePerson(Request $request)
    {
        $search = $request->input('search');
        $costRange = $request->input('cost_range');
        $status = $request->input('status');

        return inertia('AccountablePerson', [
            'accountablePerson' => $this->accountablePersonService->filterAndPaginateAccountablePerson($search, $costRange, $status),
        ]);
    }

    public function store(AccountablePersonStoreRequest $request)
    {
        $this->accountablePersonService->createAccountablePerson(
            $request->validated()
        );

        return redirect()->back()->with('success', 'Accountable Person added successfully.');
    }
}
