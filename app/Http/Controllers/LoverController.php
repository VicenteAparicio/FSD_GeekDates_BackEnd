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
        $logUser = auth()->user();

        $lover = Lover::create([
            'user_a_id'=>$request->user_a_id,
            'user_b_id'=>$request->user_b_id
        ]);
        
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
    public function destroy(Lover $Lover)
    {
        //
    }
}
