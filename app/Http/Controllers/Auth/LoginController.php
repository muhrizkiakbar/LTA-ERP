<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\History;
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

    public function login(Request $request)
    {
        // dd($request->all());

        $login = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if(auth()->attempt($login)) 
        {
            $keterangan = "Login pada system dengan email <b>".$request->username."</b> berhasil dilakukan";
            $data2 = array(
                'title' => $request->username,
                'action' => '<label class="badge badge-success">LOGIN SYSTEM SUCCESS</label>',
                'desc' => $keterangan
            );

            History::create($data2);

            $callback = array(
                'message' => 'sukses_login'
            );

            echo json_encode($callback);
        }
        else
        {
            $keterangan = "Username login : <strong>'.$request->username.'</strong> tidak di temukan";
            $data2 = array(
                'title' => $request->username,
                'action' => '<label class="badge badge-warning">USERNAME NOT FOUND</label>',
                'desc' => $keterangan
            );

            History::create($data2);
            
            $callback = array(
                'message' => 'error_notfound'
            );

            echo json_encode($callback);
        }
        
    }
}
