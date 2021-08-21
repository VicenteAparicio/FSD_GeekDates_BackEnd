<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class userController extends Controller
{

    // SHOW ALL USERS (ADMIN)

    public function allUsers()
    {
        $user = auth()->user();

        if ($user->isAdmin) {

            $allUsers = User::all();

            return response()->json([
                'success' => true,
                'data' => $allUsers
            ], 200);

        }else{      

            return response()->json([
                'success' => false,
                'messate' => 'You need admin authorization'
            ], 400);

        }
    }


    // SHOW ALL ACTIVE USERS (ADMIN)

    public function activeUsers()
    {
        $user = auth()->user();

        if ($user->isAdmin) {

            $activeUsers = User::where('isActive', true)->get();

            return response()->json([
                'success' => true,
                'data' => $activeUsers
            ], 200);

        }else{      

            return response()->json([
                'success' => false,
                'messate' => 'You need admin authorization'
            ], 400);

        }
    }


    // SHOW ALL USERS EXCEPT CURRENT USER LOGGED

    public function allPlayers()
    {
        $users = User::where('id', '!=', auth()->id())->where('isActive', true)->get();

        if ($users) {

            return response()->json([
                'success' => true,
                'data' => $users
            ], 200);

        }else{      

            return response()->json([
                'success' => false,
                'messate' => 'You need admin authorization'
            ], 400);

        }
    }

    // SHOW DEFAULT SEARCH BASED ON USER PREFERENCES

    public function defaultSearch(Request $request)
    {

        $players = User::where('id', '!=', auth()->id())
            ->where('gender', $request->lookingfor)
            ->where('lookingfor', $request->gender)
            ->orWhere([
                ['lookingfor', 'both'],
                ['gender', $request->lookingfor]])
            ->get();


        if (!$players->isEmpty()) {

            return response()->json([
                'success' => true,
                'data' => $players
            ], 200);

        }else{      

            return response()->json([
                'success' => false,
                'messate' => 'Error'
            ], 400);

        }
    }

    // SHOW USER BY ID 

    public function userById(Request $request)
    {
        $logUser= auth()->user();
        $user = User::find($request->user_id);
    
        if ($logUser) {

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);

        } else {

            return response()->json([
                'success' => false,
                'messate' => 'You need admin authorization'
            ], 400);

        }

        return response()->json([
            'success'=>false,
            'message'=>'User can not be found'
        ], 500);
    }


    // SHOW USER BY NAME

    public function userByName(Request $request)
    {
        $user = User::where('username', $request->user_name)->get();
    
        if ($user) {

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);

        } 

        return response()->json([
            'success'=>false,
            'message'=>'User can not be found'
        ], 500);
    }


    // UPDATE USER

    public function update(Request $request)
    {
        $logUser = auth()->user();
        $user = User::find($request->user_id);

        if ($user) {
            
            $userUp = $user->update(request()->all());

            if($userUp) {

                return response()->json([
                    'success' => true,
                    'message'=>'User updated',
                    'user' => $user
                ], 200);

            } else {

                return response()->json([
                    'success'=>false,
                    'message'=>'User can not be updated'
                ], 500);
            }

        } else {

            return response()->json([
                'success'=>false,
                'message'=>'You don\'t have permission'
            ], 500);

        }
    }


    // DEACTIVATE USER

    public function destroy(Request $request)
    {
        
        $logUser = auth()->user();

        $user = User::find($request->user_id);
        
        if ($logUser->id == $request->user_id || $logUser->isAdmin == true) {

            $user->isActive = 0;
            $user->save();

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);

        } else {

            return response()->json([
                'success' => false,
                'message' => 'You can not delete this user'
            ], 400);

        }
    }


    // LOG OUT

    public function logout(Request $request)
    {
        $user = auth()->user();
        $userOut = $request->user()->token()->revoke();

        if ($userOut) {
            return response()->json([
                'success' => true,
                'message' => $user->userName . ' successfully logged out'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error, you can\'t leave us.'
            ], 500);
        }
    }
}
