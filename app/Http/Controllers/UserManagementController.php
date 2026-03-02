<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserManagementController extends Controller
{
   public function UserManagement()
   {
       return inertia('UserManagement');
   }
}
