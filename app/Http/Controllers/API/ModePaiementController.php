<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ModePaiement;

class ModePaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modePaiements = ModePaiement::with('systemePaiements')->get();
        return response()->json($modePaiements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'MDP_LIBELLE' => ['required', 'string', 'unique:mode_paiements'],
        ]);
        $modePaiement = ModePaiement::create($request->all());
        // Attach systemePaiements if provided
        if ($request->has('systeme_paiements')) {
            $modePaiement->systemePaiements()->attach($request->input('systeme_paiements'));
        }
        return response()->json($modePaiement, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModePaiement  $modePaiement
     * @return \Illuminate\Http\Response
     */
    public function show(ModePaiement $modePaiement)
    {
        $modePaiement->load('systemePaiements');
        return response()->json($modePaiement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModePaiement  $modePaiement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModePaiement $modePaiement)
    {
        $request->validate([
            'MDP_LIBELLE' => ['required', 'string', Rule::unique('mode_paiements')->ignore($modePaiement->MDP_ID_MOD_PAIEMENT, 'MDP_ID_MOD_PAIEMENT')],
        ]);
        $modePaiement->update($request->all());

        // Sync systemePaiements if provided
        if ($request->has('systeme_paiements')) {
            $modePaiement->systemePaiements()->sync($request->input('systeme_paiements'));
        }
        return response()->json($modePaiement, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModePaiement  $modePaiement
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModePaiement $modePaiement)
    {
        $modePaiement->systemePaiements()->detach(); // Detach associated systems
        $modePaiement->delete();
        return response()->json(null, 204);
    }
}
