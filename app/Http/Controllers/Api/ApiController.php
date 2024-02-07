<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    //  Register api  {POST}
    public function register(Request $request){

        //Data Validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        //Create User
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make( $request->password) 
        ]);

        return response()->json([
            "status" => true,
            "message" => "User created sucessfully"
        ]);

    }

    //Login api {POST}
    public function login(Request $request){

        //Data validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // Checking User login
        if(Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ])){
            //User exists
            $user = Auth::user();
            $token = $user->createToken("myToken")->accessToken;

            return response()->json([
                "status" => true,
                "message" => "User logged in successfully",
                "token" => $token
            ]);

        }else{
            return response()->json([
                "status" => false,
                "message" => "Invalid login details"
            ]);
        }

    }

    //Profile Api {GET}
    public function profile(Request $request){

        $user = Auth::user();

        return response()->json([
            "status" => true,
            "message" => "Profile Information",
            "data" => $user
        ]);

    }

    //Logout api {GET}
    public function logout(){

        auth()->user()->token()->revoke();

        return response()->json([
            "status" => true,
            "message" => "User Logged Out Successfully"
        ]);

    }

}

