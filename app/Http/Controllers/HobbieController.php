<?php

namespace App\Http\Controllers;

use App\Models\Hobbie;
use Illuminate\Http\Request;

class HobbieController extends Controller
{
    // CREATE HOBBIES ROW WITH USER ID
    public function store(Request $request)
    {
        $checkhobbie = Hobbie::where('user_id', $request->user_id)->get();

        if ($checkhobbie->isEmpty()) {

            $hobbie = Hobbie::create([
                
                'user_id'=>$request->user_id,
                'tablegames'=>$request->tablegames,
                'rolegames'=>$request->rolegames,
                'videogames'=>$request->videogames,
                'cosplay'=>$request->cosplay,
                'anime'=>$request->anime,
            ]);

            if ($hobbie) {

                return response()->json([
                    'success' => true,
                    'message'=>'Hobbies created',
                    'data' => $hobbie
                ], 200);

            } 

        // UPDATE IN CASE ALREADY EXIST
        } else if ($checkhobbie) {
            $updated = $checkhobbie[0]->update($request->all());
            $hobbie = Hobbie::where('user_id', $request->user_id)->get();

            if ($updated) {

                return response()->json([
                    'success' => true,
                    'message'=>'Hobbies updated',
                    'data'=>$hobbie
                ], 200);
            }
        }

        return response()->json([
            'success'=>false,
            'message'=>'Error'
        ], 500);

    }
}
