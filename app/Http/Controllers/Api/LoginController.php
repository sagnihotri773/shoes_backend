<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){
        $this->validate($request, [
            'email'=>['required','email'],
            'password'=>['required']
        ]);

            $user = User::where('email',$request->email)->first();
            $statusCode=401;
            $response=['status' => false,'msg'=>'User Not Exist'];
            if($user){
                if(Auth::attempt($request->only(['email','password']))){
                    $token = Auth::user()->createToken('bearerToken')->plainTextToken;
                    $statusCode=200;
                    $response=['token' => $token,'message'=>'User login Success','user'=>Auth::user(),'status'=>true];
                }else{
                    $statusCode=401;
                    $response=['status' => false,'message'=>'Invalid Credential'];
                }
            }

            return response()->json($response, $statusCode);
    }
}
