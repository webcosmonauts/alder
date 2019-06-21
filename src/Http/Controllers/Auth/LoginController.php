<?php
namespace Webcosmonauts\Alder\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/alder';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() {
        return view('alder::auth.login');
    }

    public function username() {
        return 'email';
    }

    function checklogin(Request $request){
        $this->validate($request, [
            'input-email' => 'required',
            'input-password' => 'required',
        ]);

        $user_data = array(
            'email'  => $request->get('input-email'),
            'password' => $request->get('input-password')
        );

        if (!Auth::attempt($user_data)) {
            return redirect('/');
        }

        if (Auth::check()) {
            return redirect('/');
        }
    }
}
