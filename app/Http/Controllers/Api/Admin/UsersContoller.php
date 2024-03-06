<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersContoller extends Controller
{
    
    public function index(){
        $users = User::orderBy("id","desc")->paginate(10);
        return response()->json($users);
    }

    public function store(Request $request){}

    public function show($id){
        $user = User::find($id);
        return response()->json($user);
    }
}
