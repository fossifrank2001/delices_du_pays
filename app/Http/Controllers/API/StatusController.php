<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequestForm;
use App\Models\Statut;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StatusController extends Controller
{
    /**
     * @lrd:start
     * Hello markdown
     * Free `code` or *text* to write documentation in markdown
     * @lrd:end
    */
    public function index()
    {
        $Status = Statut::all();
        return response()->json([
            'success' => 'Statut successfully listed',
            'Status' => $Status
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StatusRequestForm $request)
    {
        try {
            // Validate the request data
            $statut = Statut::create($request->validated());
            return response()->json([
                'success' => 'Statut successfully created',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Statut $statut)
    {
        if(!$statut){
            return response()->json([
                'error' => 'statut does\'t exist'
            ], 402);
        }
        return response()->json([
            'success' => 'statut found',
            'statut'=>$statut
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StatusRequestForm $request, Statut $statut)
    {
         // Validate the request data against the RoleRequestForm rules
         try {
            $request->validate($request->rules());
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }

        if (!$statut) {
            return response()->json(['error' => 'Statut not found'], 404);
        }

        try {
            $statut->update($request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 401);
        }

        return response()->json(['success' => 'Statut updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Statut $statut)
    {
        if(!$statut || $statut->accesses->count()>0 || $statut->comptes->count()>0){
            return response()->json([
                'error' => 'You can\'t delete this resource'
            ], 402);
        }
        $statut ->delete();
        return response()->json(['success' => 'Statut delete successfully'], 200);
    }
}
