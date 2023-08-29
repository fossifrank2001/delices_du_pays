<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequestForm;
use App\Models\Role;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json([
            'success' => 'Role successfully listed',
            'roles' => $roles
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    /**
     * @param RoleRequestForm $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleRequestForm $request)
    {
        try {
            \DB::beginTransaction();
            // Validate the request data
            $validated = $request->validated();
            $role = Role::create(['ROL_LIBELLE' => $validated['ROL_LIBELLE']]);
            // Attach permissions to the role based on the request data
            $permissionIds = $request->input('permissions', []);
//            dd($permissionIds);
            $role->permissions()->attach($permissionIds);
            \DB::commit();
            return response()->json([
                'success' => 'Role successfully created',
            ], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function show(Role $role)
    {
        if(!$role){
            return response()->json([
                'error' => 'role does\'t exist'
            ], 402);
        }
        $role= $role->load('permissions');
        return response()->json([
            'success' => 'Role found',
            'role'=>$role
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequestForm $request, Role $role)
    {
        // Validate the request data against the RoleRequestForm rules
        try {
            $request->validate($request->rules());
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        try {
            $validated = $request->validated();
            $role->update(['ROL_LIBELLE' => $validated['ROL_LIBELLE']]);

            // Sync permissions with the role based on the request data
            $permissionIds = $request->input('permissions', []);
            $role->permissions()->sync($permissionIds);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 401);
        }

        return response()->json(['success' => 'Role updated successfully'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if(!$role || $role->access->count() > 0){
            return response()->json([
                'error' => 'You can\'t delete this resource'
            ], 402);
        }

        // Detach all permissions from the role
        $role->permissions()->detach();

        //delete the role
        $role->delete();
        return response()->json(['success' => 'Role delete successfully'], 200);
    }
}
