<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Lover;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // CHECK IF THERE ARE MESSAGES AND SEND THEM
    public function check (Request $request)
    {
        $message = Message::where('match_id', $request->match_id)->get();

        if ($message->isEmpty()) {

            return response()->json([
                'success'=>false,
                'message'=>'There is no messages here',
            ],400);
        } 

        return response()->json([
            'success'=>true,
            'message' =>'Here you have your messages',
            'data'=>$message
        ], 200);
    }

    
    public function store(Request $request)
    {
        $message = Message::where('match_id', $request->match_id)->get();
        $match = Lover::find($request->match_id);
        
        if (!$match && $message->isEmpty()) {

            return response()->json([
                'success'=>true,
                'message'=>'You are not in the party'
            ], 400);

        } else {

            $message = Message::create([
                'text'=>$request->text,
                'match_id'=>$request->match_id,
                'user_from_id'=>$request->user_from_id,
                'user_to_id'=>$request->user_to_id
            ]);

            if (!$message) {
                return response()->json([
                    'success'=>false,
                    'message'=>'Message not created ' 
                ], 500);
            }

            return response()->json([
                'success'=>true,
                'message'=>'Done!',
                'data'=>$message->toArray()
            ], 200);

        }
    }

}
