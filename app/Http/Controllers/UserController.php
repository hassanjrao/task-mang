<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->get('q');
        $users= User::where('name', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%")
            ->orWhere('phone', 'like', "%$q%")
            ->orWhere('username', 'like', "%$q%")
            ->select('id', 'name', 'email', 'phone', 'username')
            ->orderBy('name')
            ->get();


        return response()->json($users);
    }
}
