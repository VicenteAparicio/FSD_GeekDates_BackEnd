<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hobbie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // SHOW LOVERS MATCHES

    public function loverMatches(Request $request)
    {
        // $matches = DB::table('lovers')
        //     ->where([
        //         ['user_a_id', $request->user_id],
        //         ['isActive', true]])
        //     ->orWhere([
        //         ['user_b_id', $request->user_id],
        //         ['isActive', true]])
        //         ->get();

        // $matches = DB::table('users')
        //     ->join('lovers', function($join){
        //         $join->on('users.id', '=', 'lovers.user_b_id')
        //         ->where('users.id', '!=', auth()->id())
        //         ->orOn('users.id', '=', 'lovers.user_a_id')
        //         ->where('users.id', '!=', auth()->id());
        //     })
        // ->get();

        // $matches = DB::table('lovers')
        //     ->join('users', function($join){
        //         $join->on()->where('lovers.user_a_id', '=', auth()->id())->orWhere('lovers.user_b_id', '=', auth()->id());
        //         // ->orOn('lovers.user_b_id', '=', auth()->id());
        //     })
        // ->get();

        $matches = DB::table('users')
            ->join('lovers', function($join){
                $join->on('users.id', '=', 'lovers.user_b_id')
                    ->where('lovers.user_a_id', '=', auth()->id())
                    ->where('lovers.isActive','=', 1)
                    ->orOn('users.id', '=', 'lovers.user_a_id')
                    ->where('lovers.user_b_id', '=', auth()->id())
                    ->where('lovers.isActive', '=', 1)
            ;})
        ->get();

        


        if (!$matches->isEmpty()) {

            return response()->json([
                'success' => true,
                'data' => $matches,
                // 'hobbie' => $hobbie
            ], 200);

        }else{      

            return response()->json([
                'success' => false,
                'messate' => 'Error'
            ], 400);
        }
    }

        // SHOW SEARCH BASED ON USER PREFERENCES

    public function defaultSearch(Request $request)
    {
        // IN CASE LOOKING FOR IS BOTH I HAVE TO CHANGE THE SEARCH METHOD
        if ($request->lookingfor == "both"){
            
            $players = DB::table('users')
                // JOIN HOBBIES TABLES TO USER TABLES BASED ON THEIR USER_ID
                ->join('hobbies', 'users.id', '=', 'hobbies.user_id')

                // NO MATTER GENDERS, ONLY WHAT THEY ARE LOOKING FOR MATCHES WITH THE ACTUAL USER BEING
                ->where('lookingfor', $request->gender)
                ->where('isActive', true)

                // IN CASE THE PLAYER MATCH GENDER BUT IS BISEXUAL AND IS LOOKING FOR BOTH GENDERS
                ->orWhere([
                    ['lookingfor', 'both'],
                    ['isActive', true]])
                ->get();


        
        } else {

            // REST OF SEARCHS BASED ON THE DEFAULT USER PREFERENCES NOT LOOKING FOR BOTH GENDERS
            $players = DB::table('users')
                // JOIN HOBBIES TABLES TO USER TABLES BASED ON THEIR USER_ID
                ->join('hobbies', 'users.id', '=', 'hobbies.user_id')

                // THIS EXCLUSION IS RESOLVED ON FRONT-END
                // ->where('id', '!=', auth()->id())

                // SEARCH BASED ON "LOOKING FOR" AND "GENDER" PLAYERS-USERS SENT BY ACTUAL USER BY DEFAULT
                ->where('gender', $request->lookingfor)
                ->where('lookingfor', $request->gender)
                ->where('isActive', true)
                
                // IN CASE THE PLAYER MATCH GENDER BUT IS BISEXUAL AND IS LOOKING FOR BOTH GENDERS
                ->orWhere([
                    ['lookingfor', 'both'],
                    ['gender', $request->lookingfor],
                    ['isActive', true]])
                ->get();
        }

        if (!$players->isEmpty()) {

            return response()->json([
                'success' => true,
                'data' => $players,
            ], 200);

        }else{      

            return response()->json([
                'success' => false,
                'messate' => 'Players not found'
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
