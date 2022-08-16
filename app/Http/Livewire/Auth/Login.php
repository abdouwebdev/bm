<?php

namespace App\Http\Livewire\Auth;

use App\Services\AuthService;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    protected $redirects = RouteServiceProvider::HOME;
    protected $redirects_user = RouteServiceProvider::USER;
    protected $redirects_super_admin = RouteServiceProvider::SUPER_ADMIN;
    public $email, $password, $remember;

    protected $rules = [
        'email' => 'required|exists:users',
        'password' => 'required|min:6'
    ];

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login(AuthService $auth)
    {
        $data = [
            'email' => $this->email,
            'password' => $this->password
        ];

        $customMessages = [
            'exists' => "Email doesn't exist.",
            'min' => "The password must be at least 8 characters"
        ];

        $credentials = $this->validate($this->rules, $customMessages, $data);
        $remember = !empty($this->remember);

        try {
            if ($auth->login($credentials, $remember) AND Auth::user()->role === 'admin') {
                return redirect()->route($this->redirects);
            }elseif($auth->login($credentials, $remember) AND Auth::user()->role === 'user'){
                return redirect()->route($this->redirects_user);
            }elseif($auth->login($credentials, $remember) AND Auth::user()->role === 'superadmin'){
                return redirect()->route($this->redirects_super_admin);
            }
            else {
                session()->flash('error', 'Your password is incorrect.');
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
