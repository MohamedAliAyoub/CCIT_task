<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    use AuthenticatesUsers;


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $name;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->name = $this->findName();
    }

     /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */

    public function findName()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo(){
        if( Auth()->user()->role == 1){
            return route('admin.dashboard');
        }
        elseif( Auth()->user() == 2){
            return route('user.dashboard');
        }
    }


    public function name()
    {
        return $this->name;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'login'    => 'required',
            'password' => 'required',
        ]);

        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL )
            ? 'email'
            : 'name';

        $request->merge([
            $login_type => $request->input('login')
        ]);

        if (Auth::attempt($request->only($login_type, 'password'))) {
            if( auth()->user()->type == 0 ){
                return redirect()->route('admin.dashboard');
            }
            elseif( auth()->user()->type ==1 ) {
                if(auth()->user()->is_active ==1)
                    return redirect()->route('user.dashboard');
                else
                    return redirect()->back()
                        ->withInput()
                        ->withErrors([
                            'login' => 'email is in active.',
                        ]);
            }
        }

        return redirect()->back()
            ->withInput()
            ->withErrors([
                'login' => 'These credentials do not match our records.',
            ]);
    }




    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }


    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();

        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }

    protected function _registerOrLoginUser($data)
    {
        $user = User::where('email' , $data->email)->first();
        if(!$user){
            // dd($data);
            $user = new User();
            $user->name = $data->name ?? $data->nickname;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->save();
        }

       Auth::login($user);
    }
}
