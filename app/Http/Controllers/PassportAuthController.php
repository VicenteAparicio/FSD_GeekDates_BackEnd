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
            // 'name'=>'required|min:3',
            // 'surname'=>'required|min:3',
            // 'age'=>'required',
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
            // 'name'=>$request->name,
            // 'surname'=>$request->surname,
            // 'age'=>$request->age,
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
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' =>$token], 200);
        } else {
            return response()->json(['error'=>'Tu puta Unauthorised'], 401);
        }
    }
}
