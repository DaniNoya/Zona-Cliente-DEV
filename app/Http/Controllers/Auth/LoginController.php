<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
    protected $redirectTo = '/';

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\HttpRequest  $request
     * @return array
     */
    protected function credentials($request)
    {
        $user = $request->input('user');
        return [
            'user' => $user,
            'password' => $request->input('password')
        ];
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'user' => 'required|string',
            'password' => 'required|string',
            'privacy' => 'required|accepted',
        ], [
            'privacy.required' => 'Debes aceptar las políticas de privacidad para continuar.',
            'privacy.accepted' => 'Debes aceptar las políticas de privacidad para continuar.'
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->getProvider()->retrieveByCredentials(['user' => $request->user]);

        if (!$user) {
            throw ValidationException::withMessages([
                'user' => ['El usuario no existe en nuestros registros.']
            ]);
        }

        throw ValidationException::withMessages([
            'password' => ['La contraseña ingresada es incorrecta.']
        ]);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect($this->redirectTo);
        }
        return view('auth.login');
    }
}
