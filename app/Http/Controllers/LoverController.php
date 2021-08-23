<?php

namespace App\Http\Controllers;

use App\Models\Lover;
use App\Models\User;
use Illuminate\Http\Request;

class LoverController extends Controller
{
    // SAVE LOVER ROW WAITING FOR MATCH
    // ACTIVE MATCH IN CASE LOVER ROW WAS CREATED BY THE OTHER PLAYER
    public function store(Request $request)
    {
        $check = Lover::where('user_a_id', $request->user_b_id)
                    ->where('user_b_id', $request->user_a_id)->get();

        if (!$check->isEmpty() && $check[0]->isActive==false) {
            $check[0]->isActive = true;
            $check[0]->save();
            return response()->json([
                'success' => true,
                'message' => 'MATCH!!!'
            ], 200);

        } else if (!$check->isEmpty() && $check[0]->isActive==true) {
            return response()->json([
                'success' => false,
                'message' => 'Already matched'
            ], 200);
            
        } else if ($check->isEmpty()) {

            $checked = Lover::where('user_a_id', $request->user_a_id)
                ->where('user_b_id', $request->user_b_id)->get();

            if (!$checked->isEmpty()){
                return response()->json([
                    'success' => false,
                    'message' => 'You have to wait for the other player'
                ], 400);
            }
        }


        $lover = Lover::create([
            'user_a_id'=>$request->user_a_id,
            'user_b_id'=>$request->user_b_id
        ]);

        if($lover) {
            return response()->json([
                'success' => true,
                'message'=>'Waiting for match'
            ], 200);

        } else {

            return response()->json([
                'success'=>false,
                'message'=>'Lover not created'
            ], 500);
        }

        
    }

    // DELETE LOVER ROW (HAS TO BE DELETED IN CASE THEY WANT TO TALK IN A FUTURE)
    public function destroy(Request $request)
    {
        $check = Lover::where('user_a_id', $request->user_b_id)
                    ->where('user_b_id', $request->user_a_id)->get();

        if ($check->isEmpty()) {

            $check = Lover::where('user_a_id', $request->user_a_id)
                ->where('user_b_id', $request->user_b_id)->get();
            
            if ($check->isEmpty()){
                return response()->json([
                    'success'=>false,
                    'message'=>'Lover not matched anymore'
                ], 400);
            }
        }

        $check[0]->delete();

        return response()->json([
            'success' => true,
            'message' => 'UNMATCH!!!'
        ], 200);

    }
}
