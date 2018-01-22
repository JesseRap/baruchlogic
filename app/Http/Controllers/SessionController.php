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

      $user = User::where([
        ['key', '=', $key],
        ['admin', '=', '0']
        ])->first();


      // Login the user
      auth()->login($user);


      return redirect()->home();

    }

    public function logout()
    {
      auth()->logout();

      return redirect()->home();
    }

    public function adminlogin(Request $request)
    {
      // Validate the form

      $validatedData = $request->validate([
        'key' => 'required'
      ]);

      $key = $request->input('key');

      $user = User::where([
        ['key', '=', $key],
        ['admin', '=', '1']
        ])->first();


      // Login the user
      if (!is_null($user))
      {
        auth()->login($user);
        return redirect()->route('dashboard');
      }
      else
      {
        return redirect()->home();
      }

    }

}
