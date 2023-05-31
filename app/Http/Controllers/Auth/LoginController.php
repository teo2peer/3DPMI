<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\Models\User;
use Auth;
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

    public function redirectToProvider()
{
    return Socialite::driver('google')->redirect();
}

 /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        // only allow people with @company.com to login
        if(explode("@", $user->email)[1] !== 'gmail.com'){
            return redirect()->to('/errorNotAllowed');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);

        } else {
            //'name',
            // 'email',
            // 'email_verified',
            // 'google_id',
            // 'rol',
            // 'departamento',
            // 'subdepartamento',
            // 'cargo',
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'email_verified' => true,
                'google_id' => $user->id,
                'rol' => 'user',
                
            ];
            $newUser = User::add($data);

            auth()->login($newUser, true);
        }
        return redirect()->to('/checkIsAllowed');
    }

    public function logout()
    {
        // Logout user loged in witrh google
        Auth::logout();
        return redirect('/');
    }
   
}