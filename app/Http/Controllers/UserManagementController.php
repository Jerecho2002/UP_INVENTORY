<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function userManagement(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        return inertia('UserManagement', [
            'users' => $this->userService->filterAndPaginateUsers($search, $status),
        ]);
    }
}
