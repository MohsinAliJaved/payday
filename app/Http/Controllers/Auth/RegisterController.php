<?php

namespace App\Http\Controllers\Auth;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\Auth\TypeSeeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\Status\StatusSeeder;
use Illuminate\Support\Facades\Validator;
use Database\Seeders\Auth\UserTableSeeder;

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
    public function showRegistrationForm()
    {
        return view('auth.super-admin.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'domain'=> ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $tenant = Tenant::create();
        $tenant->domains()->create(['domain' => $request->domain.'.localhost']);
        dd($tenant);
        // Artisan::call("tenants:seed --tenants={$tenant['id']}");
        
        return redirect()->back();
    }
}
