<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hobbie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{

    // SHOW ALL USERS EVEN IF THE ARE NOT ACTIVE (ADMIN) (NOT IN USE)
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


    // SHOW ALL ACTIVE USERS (ADMIN) (NOT IN USE)
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


    // SHOW ALL USERS EXCEPT CURRENT USER LOGGED (NOT IN USE)
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

    // GET MATCHES INFO FROM ACTUAL USER
    public function loverMatches()
    {
        $matches = DB::table('users')
            // GET LOVERS TABLE TO COMPARE USERS ID
            ->join('lovers', function($join){

                // COMPARE FIRST USER ID IS ON THE LOVERS ROW
                $join->on('users.id', '=', 'lovers.user_b_id')

                    // CHECK ACTUAL USER IS ON THE LOVERS ROW
                    ->where('lovers.user_a_id', '=', auth()->id())
                    ->where('lovers.isActive','=', 1)

                    // COMPARE THE OTHER USER ID ON LOVERS ROW
                    ->orOn('users.id', '=', 'lovers.user_a_id')
                    ->where('lovers.user_b_id', '=', auth()->id())
                    ->where('lovers.isActive', '=', 1)
            ;})
            ->get();

        if (!$matches->isEmpty()) {

            return response()->json([
                'success' => true,
                'data' => $matches,
            ], 200);

        }else{      

            return response()->json([
                'success' => false,
                'messate' => 'Error'
            ], 400);

        }
    }

    
    // GET PLAYERS BASED ON USER PREFERENCES
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

    // SHOW USER BY ID (NOT IN USE)
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


    // SHOW USER BY NAME (NOT IN USE)
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

    // UPDATE PASSWORD
    public function upPassword (Request $request)
    {
        $logUser = auth()->user();
        $user = User::find($request->user_id);

        if ($request->user_id == $logUser->id || $logUser->isAdmin == true) {

            $userUp = $user->fill([
                'password'=>bcrypt($request->password)
            ])->save();

            if($userUp) {

                return response()->json([
                    'success' => true,
                    'data' => $user

                ], 200);

            } else {

                return response()->json([
                    'success'=>false,
                    'message'=>'Password update error'
                ], 500);
            }

        } else {

            return response()->json([
                'success'=>false,
                'message'=>'You don\'t have permission'
            ], 400);
            
        }
    }

    // UPLOAD PIC
    public function uploadPic (Request $request)
    {
        $logUser = auth()->user();
        $user = User::find($logUser->id );

        if ($user) {

            // $path = Storage::disk('local')->put(request()->all(), 'images');

            // echo asset('storage/file.txt');

            // $path = $request->file('image')->store('images', 'public');
            dd(request()->all())->store('images', 'public');
            
            // $path = $request->image->store('images');

            return response()->json([
                'success' => true,
                'data' => $user

            ], 200);

            $userUp = $user->fill([
                'urlpic'=>$user->nick
            ])->save();

            if($userUp) {

                return response()->json([
                    'success' => true,
                    'data' => $user

                ], 200);

            } else {

                return response()->json([
                    'success'=>false,
                    'message'=>'Image upload error'
                ], 500);
            }

        } else {

            return response()->json([
                'success'=>false,
                'message'=>'You don\'t have permission'
            ], 400);
            
        }
    }

        // // UPLOAD PIC
    // public function uploadPic (Request $request)
    // {
    //     $logUser = auth()->user();
    //     $user = User::find($logUser->id );

    //     if ($user) {

            
    //         $path = $request->image->store('images');

    //         return response()->json([
    //             'success' => true,
    //             'data' => $user

    //         ], 200);

    //         $userUp = $user->fill([
    //             'urlPic'=>$path
    //         ])->save();

    //         if($userUp) {

    //             return response()->json([
    //                 'success' => true,
    //                 'data' => $user

    //             ], 200);

    //         } else {

    //             return response()->json([
    //                 'success'=>false,
    //                 'message'=>'Image upload error'
    //             ], 500);
    //         }

    //     } else {

    //         return response()->json([
    //             'success'=>false,
    //             'message'=>'You don\'t have permission'
    //         ], 400);
            
    //     }
    // }



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
            ], 200);

        } else {

            return response()->json([
                'success' => false,
                'message' => 'Error, you can\'t leave us.'
            ], 500);

        }
    }
}
