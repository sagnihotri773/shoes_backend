<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'mobile'=> $validatedData['mobile'] ?? null,
            'last_name'=> $validatedData['last_name'] ?? null,
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request){
        $this->validate($request, [
            'email'=>['required','email'],
            'password'=>['required']
        ]);

            $user = User::where('email',$request->email)->first();
            $statusCode=401;
            $response=['sttaus' => false,'msg'=>'User Not Exist','user'=>null];
            if($user){
                if(Auth::attempt($request->only(['email','password']))){
                    $token = Auth::user()->createToken('bearerToken')->plainTextToken;
                    $statusCode=200;
                    $response=['token' => $token,'msg'=>'login Success','user'=>Auth::user(),'status'=>true];
                }else{
                    $statusCode=401;
                    $response=['sttaus' => false,'msg'=>'Invalid Credential','user'=>null];
                }
            }

            return response()->json($response, $statusCode);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}

