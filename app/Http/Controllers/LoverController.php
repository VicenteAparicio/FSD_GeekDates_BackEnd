<?php

namespace App\Http\Controllers;

use App\Models\Lover;
use Illuminate\Http\Request;

class LoverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check = Lover::where('user_a_id', $request->user_b_id)
                    ->where('user_b_id', $request->user_a_id)->get();

        if (!$check->isEmpty()) {
                $check[0]->isActive = true;
                $check[0]->save();

                return response()->json([
                    'success' => true,
                    'message' => 'MATCH!!!'
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lover  $Lover
     * @return \Illuminate\Http\Response
     */
    public function show(Lover $Lover)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lover  $Lover
     * @return \Illuminate\Http\Response
     */
    public function edit(Lover $Lover)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lover  $Lover
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lover $Lover)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lover  $Lover
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $check = Lover::where('user_a_id', $request->user_b_id)
                    ->where('user_b_id', $request->user_a_id)->get();

        if ($check->isEmpty()) {

            $checked = Lover::where('user_a_id', $request->user_a_id)
                ->where('user_b_id', $request->user_b_id)->get();
            
            if ($checked->isEmpty()){
                return response()->json([
                    'success'=>false,
                    'message'=>'Lover not matched anymore'
                ], 400);
            }
        }

        $check->isActive = false;
        $check->save();

        return response()->json([
            'success' => true,
            'message' => 'UNMATCH!!!'
        ], 200);

    }
}
