<?php

namespace App\Http\Controllers;

use App\Models\Hobbie;
use Illuminate\Http\Request;

class HobbieController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        
    }


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
        }
        return response()->json([
            'success'=>false,
            'message'=>'Ya rellenaste tus hobbies'
        ], 500);
        

    }


    public function show(Hobbie $hobbie)
    {
        
    }


    public function edit(Hobbie $hobbie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hobbie  $hobbie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $logUser = auth()->user();
        $hobbie = Hobbie::where('user_id', $request->user_id);

        if ($hobbie) {

            $updated = $hobbie->update($request->all());

            if ($updated) {
                return response()->json([
                    'success'=>true,
                    'message'=>'Hobbies updated'
                ], 200);
            } else {
                return response()->json([
                    'success'=>false,
                    'message'=> 'Error hobbie not updated'
                ], 500);

            }
        } else {
            return response()->json([
                'success'=>false,
                'message'=> 'You can not update this hobbies'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hobbie  $hobbie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

    }
}
