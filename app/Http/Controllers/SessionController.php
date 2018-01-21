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

      $user = User::where('key', $key)->first();


      // Login the user
      auth()->login($user);

      $_SESSION['loggedin'] = TRUE;

      return redirect()->home();

    }

    public function logout()
    {
      auth()->logout();

      unset($_SESSION['loggedin']);

      return redirect()->home();
    }

}
