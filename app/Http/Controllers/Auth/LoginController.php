<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'login'; // use the input field name
    }

    protected function credentials(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'login_with' => 'required|in:email,phone,username',
        ]);

        $field = $request->input('login_with'); // 'email', 'phone', or 'username'
        return [
            $field => $request->input('login'),
            'password' => $request->input('password'),
        ];
    }
}
