<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PassportAuthController extends Controller
{
    public function register(Request $request) {
        $this->validate($request, [
            'nick'=>'required|min:4',
            'name'=>'required',
            'surname'=>'required',
            'email'=>'required|email',
            'age'=>'required',
            'phone'=>'required|min:9',
            'country'=>'required',
            'city'=>'required',
            'cp'=>'required',
            'gender'=>'required',
            'sexuality'=>'required',
            'password'=>'required|min:8'
        ]);

        $user = User::create([
            'nick'=>$request->nick,
            'name'=>$request->name,
            'surname'=>$request->surname,
            'email'=>$request->email,
            'age'=>$request->age,
            'phone'=>$request->phone,
            'country'=>$request->country,
            'city'=>$request->city,
            'cp'=>$request->cp,
            'gender'=>$request->gender,
            'sexuality'=>$request->sexuality,
            'password'=>bcrypt($request->password)
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
