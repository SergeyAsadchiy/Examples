<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\MarketingUser;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('basic');
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',                // must be at least 8 characters in length
                //'regex:/[a-zA-Z]/',     // must contain at least one letter
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',        // must contain at least one digit
                //'regex:/[@$!%*#?&]/', // must contain a special character
                'confirmed'
            ]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            //'name' => $data['name'],
            'name' => $data['email'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => isset($data[User::USER_ROLE_LOREM]) ? User::USER_ROLE_LOREM : User::USER_ROLE_USER,
        ]);
    }

    public function showRegistrationForm()
    {
        SEOMeta::setTitle('Lorem');
        SEOMeta::setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod. Tempor incididunt ut labore et dolore magna aliqua');
        SEOMeta::setKeywords(
            ['Lorem', 'ipsum', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'sed', 'eiusmod', 'empor', 'incididunt', 'labore et', 'learning videos', 'empor', 'incididunt', 'labore et', 'learning videos']
        );
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return redirect(route('lorem-ipsum'));
    }

    /*public function showFaq()
    {
        return view('auth.faq');
    }

    public function showTerms()
    {
        return view('auth.terms');
    }*/
}
