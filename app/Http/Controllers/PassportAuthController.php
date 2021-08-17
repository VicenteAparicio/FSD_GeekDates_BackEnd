<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PassportAuthController extends Controller
{
    public function register(Request $request) {
        $this->validate($request, [
            'email'=>'required|email',
            'password'=>'required|min:8',
            'nick'=>'required|min:3',
            'phone'=>'required|min:9',
            'age'=>'required',
            // 'name'=>'required|min:3',
            // 'surname'=>'required|min:3',
            // 'country'=>'required',
            // 'city'=>'required',
            // 'cp'=>'required',
            // 'gender'=>'required',
            // 'sexuality'=>'required',
            
        ]);

        $user = User::create([
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'nick'=>$request->nick,
            'phone'=>$request->phone,
            'age'=>$request->age,
            // 'name'=>$request->name,
            // 'surname'=>$request->surname,
            // 'country'=>$request->country,
            // 'city'=>$request->city,
            // 'cp'=>$request->cp,
            // 'gender'=>$request->gender,
            // 'sexuality'=>$request->sexuality,
        ]);

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $user = auth()->user();
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' =>$token, 'user'=>$user], 200);
        } else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
}
