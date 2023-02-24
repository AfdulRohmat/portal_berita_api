<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationContoller extends Controller
{   
    // REGISTER USER
    public function register(Request $request)
    {   
        // MEALUKAN VALIDATION FORM HARUS TERSISI
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'username' => ['required', 'max:255'],
            'password' => ['required','min:4', ],
            'firstname' => ['required'],
            'lastname' => ['required'],
        ]);

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
        ]);

        $token = $user->createToken('myAppToken');

        return response()->json(['message' => "Berhasil membuat akun. Silahkan Login"]);
    }


    // LOGIN CONTROLLER
    public function login(Request $request)
    {
        // MEALUKAN VALIDATION FORM HARUS TERSISI
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email yang dimasukan salah.'],
                'password' => ['Password yang dimsukan salah.'],
               
            ]);
        }
     
        return (new UserResource($user))->additional([
            'token' => $user->createToken('myAppToken')->plainTextToken,
        ]);  
    }

    // LOGOUT CONTROLLER
    public function logout(Request $request)
    {
       // Revoke a specific token...
       $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => "Berhasil untuk logout"]);
    }

    // GET DATA USER
    public function getDataUser()
    {
       
        $user = Auth::user();

        // return new UserResource($user);
        return response()->json(['data' => $user]);
    }
}
