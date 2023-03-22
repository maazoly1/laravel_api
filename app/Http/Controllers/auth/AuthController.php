<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function login() {
        return response()->json("You're Login");
    }

    public function register(Request $request) {
        $rules = [
            'name' => 'required|string|max:128',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ];
        $message = [
            'name.required' => 'Please enter your Name',
            'name.string' => 'Name should be in String Format',
            'name.max' => 'Name should contain not more than 128 characters',
            'email.required' => 'Please enter your email address',
            'email.string' => 'Email should contain @ and .',
            'email.unique' => 'Please enter a unique email address',
            'password.required' => 'Please enter your Password',
            'password.confirmed' => 'Please confirm your Password',
        ];
        // var_dump($request->all());
        $validate = Validator::make($request->all(), $rules, $message);
    
        if($validate->fails()){
            $error = $validate->errors();
            return response()->json([
                'status' => false,
                'message'=> $error
            ],404);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user = User::create($data);
    
        return response()->json([
            'status' => true,
            'api_token'=> $user->createToken('API Token of '.$user->name)->plainTextToken,
            'user' => $user
        ],201); 
    }
}
