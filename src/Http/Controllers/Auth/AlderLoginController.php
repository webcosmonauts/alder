<?php
namespace Webcosmonauts\Alder\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AlderLoginController extends Controller
{

    use AuthenticatesUsers;
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function showLoginFormUsers(){
        return view('alder::login');
    }

    public function username(){
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

        if(!Auth::attempt($user_data)){
            return redirect('/');
        }

        if ( Auth::check() ) {
            return redirect('/');
        }
    }

    function logout(){
        Auth::logout();
        return redirect('/');
    }

}
