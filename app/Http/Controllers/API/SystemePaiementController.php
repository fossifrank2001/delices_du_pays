<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SystemePaiement;

class SystemePaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $systemePaiements = SystemePaiement::all();
        return response()->json($systemePaiements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $systemePaiement = SystemePaiement::create($request->all());
        return response()->json($systemePaiement, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemePaiement  $systemePaiement
     * @return \Illuminate\Http\Response
     */
    public function show(SystemePaiement $systemePaiement)
    {
        return response()->json($systemePaiement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemePaiement  $systemePaiement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemePaiement $systemePaiement)
    {
        $systemePaiement->update($request->all());
        return response()->json($systemePaiement, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemePaiement  $systemePaiement
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemePaiement $systemePaiement)
    {
        $systemePaiement->delete();
        return response()->json(null, 204);
    }
}
