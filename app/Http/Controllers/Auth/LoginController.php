<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use Symfony\Component\HttpFoundation\Session\Session;
//use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Route;
//use Session;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\RedirectsUsers;
use app\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use  RedirectsUsers, ThrottlesLogins;
    protected $redirectTo = RouteServiceProvider::HOME;


public function __construct()
{
    $this->middleware('auth:employee,student,admin', ['only'=>['index','logout']]);   
    $this->middleware('guest:student,admin,employee', ['only'=>['login']]); 
}


public function showLoginForm(){
    
    return view('auth.employee_login');
}



public function login(Request $request)
{
    if($request->isMethod('post'))
    {
        $this->validateLogin($request);
        if ($this->attemptLogin($request)) 
        {
            if ($request->hasSession())
            {
                $request->session()->put('auth.password_confirmed_at', time());
            }
            return $this->sendLoginResponse($request);   
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
    elseif($request->isMethod('get'))
    {
        return view('auth.employee_login');
    }    
    
}


protected function validateLogin(Request $request)
{
    $request->validate([ $this->username() => 'required|email','password' => 'required|string','pass'=> 'required|min:1']);
}

protected function attemptLogin(Request $request)
{
     if($request->pass == '1')
     {
         return Auth::guard('employee')->attempt($this->credentials($request));    
     }
     if($request->pass == '2')
     {
        return Auth::guard('student')->attempt($this->credentials($request));      
     }
     if($request->pass == '3')
     {
         return Auth::guard('admin')->attempt($this->credentials($request));      
     }   
}


protected function credentials(Request $request)
{
    return $request->only($this->username(), 'password');
}

protected function sendLoginResponse(Request $request)
{
    $request->session()->regenerate();
    $this->clearLoginAttempts($request);
    if (Auth::guard('student') || Auth::guard('employee')) 
    {
        return $request->wantsJson() ? new JsonResponse([], 204)   : redirect()->route('employee.dashboard');
    }
    return $request->wantsJson() ? new JsonResponse([], 204) : redirect()->route('login');
}

protected function sendFailedLoginResponse(Request $request)
{
   // Session::flash('credential mismatch! check and retry');
    throw ValidationException::withMessages([$this->username() => [trans('auth.failed')],'password'=> [trans('auth.failed')],]);
}

public function username()
{
    return 'email';
}

public function logout(Request $request)
{
    Auth::guard()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return $request->wantsJson()? new JsonResponse([], 204): redirect('/');
}


}
