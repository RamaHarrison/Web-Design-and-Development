<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:6' , 'confirmed']
        ]);

        $user = User::create($validated);

        Auth::login($user);

        return redirect('/menu');
    }
}
