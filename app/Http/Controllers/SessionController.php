<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class SessionController extends Controller
{
    public function login(Request $request)
    {
      // Validate the form

      $validatedData = $request->validate([
        'key' => 'required'
      ]);

      $key = $request->input('key');

      $user = User::where('key', '=', '$key');

      // Login the user
      auth()->login($user);

      return redirect()->home();

    }

    public function logout()
    {
      auth()->logout();

      return redirect()->home();
    }
}
