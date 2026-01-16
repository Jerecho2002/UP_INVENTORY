<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountablePersonService;
use App\Http\Requests\AccountablePersonStoreRequest;
use App\Http\Requests\AccountablePersonUpdateRequest;
use App\Models\AccountablePerson;

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

    public function update(AccountablePersonUpdateRequest $request, $id)
    {
        $this->accountablePersonService->updateAccountablePerson($id, $request->validated());

        return redirect()->back()->with('success', 'Accountable Person updated successfully.');
    }

    public function destroy($id)
    {
        $supplier = AccountablePerson::findOrFail($id);
        $supplier->delete();

        return redirect()->back()->with('success', 'Accountable Person deleted successfully.');
    }
}
