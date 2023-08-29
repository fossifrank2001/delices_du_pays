<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccessRequestForm;
use App\Models\Access;
use App\Models\Role;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $accesses = Access::with(['compte', 'role', 'statut'])->get();

        return response()->json([
            'message' => 'Access listed successfully',
            'Accesses' => $accesses
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccessRequestForm $request)
    {
        $validateData = $request->validated();
        $account = User::where('CTE_ID_COMPTE', $validateData['CTE_ID_COMPTE'])->first();
        $role = Role::where('ROL_ID_ROLE', $validateData['ROL_ID_ROLE'])->first();
        $statut = Statut::where('STA_ID_STATUT', $validateData['STA_ID_STATUT'])->first();

        if (!$account || !$role || !$statut) {
            return response()->json(['message' => 'Invalid account, role, or statut'], 422);
        }

        $access = new Access();
        $access->compte()->associate($account);
        $access->statut()->associate($statut);
        $access->role()->associate($role);
        $access->save();
        return response()->json([
            'message' => 'Access created successfully',
            'access' => $access
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Access $access)
    {
        $access = $access->load(['compte', 'role', 'statut']);
        return response()->json([
            'message' => 'Access retrieved successfully',
            'access' => $access
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(AccessRequestForm $request, Access $access)
    {
        $validatedData = $request->validated();

        // Update the associated models (if needed)
        $account = User::where('CTE_ID_COMPTE', $validatedData['CTE_ID_COMPTE'])->first();
        $role = Role::where('ROL_ID_ROLE', $validatedData['ROL_ID_ROLE'])->first();
        $statut = Statut::where('STA_ID_STATUT', $validatedData['STA_ID_STATUT'])->first();

        if (!$account || !$role || !$statut) {
            return response()->json(['message' => 'Invalid account, role, or statut'], 422);
        }

        // Update the associations and save the changes
        $access->compte()->associate($account);
        $access->statut()->associate($statut);
        $access->role()->associate($role);
        $access->save();

        return response()->json([
            'message' => 'Access updated successfully',
            'access' => $access
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Access $access)
    {
        // Delete the access record
        $access->delete();

        return response()->json([
            'message' => 'Access deleted successfully'
        ], 200);
    }

}
