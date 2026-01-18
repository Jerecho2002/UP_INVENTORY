<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SSOService;

class UPSSOController extends Controller
{
    public function __construct(
        protected SSOService $ssoService,
    ) {}

    public function ssoRedirect(Request $request)
    {   
        $response = $this->ssoService->verifySSOToken($request);
        // dd(gettype($response));
        $data = $response->getData(true);
        if (!$data['status']) {
            abort(401, 'SSO Token verification failed');
        }

        $email  = $request->input('client_id');
        // dd($email);
        $user = $this->ssoService->findByEmail($email);
        // dd($user);
        $user = $this->ssoService->findByEmail($email);
        return $response;
    }
}
