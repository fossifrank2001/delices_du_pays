<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequestForm;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();
        return response()->json([
            'success' => 'category successfully listed',
            'categories' => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequestForm $request)
    {
        try {
            // Validate the request data
            Categorie::create($request->validated());
            return response()->json([
                'success' => 'category successfully created',
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
    public function show(string $id)
    {
        $category = Categorie::where('CAT_ID_CATEGORY', $id)->first();
        if(!$category){
            return response()->json([
                'error' => 'category does\'t exist'
            ], 402);
        }
        return response()->json([
            'success' => 'Category read successfully',
            'category'=>$category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequestForm $request, string $id)
    {
        // Validate the request data against the RoleRequestForm rules
        try {
            $request->validate($request->rules());
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
        $category = Categorie::where('CAT_ID_CATEGORY', $id)->first();

        if (!$category) {
            return response()->json(['error' => 'category not found'], 404);
        }

        try {
            $category->update($request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 401);
        }

        return response()->json(['success' => 'category updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /**
         * @var App\Models\Categorie $category
         */
        $category = Categorie::where('CAT_ID_CATEGORY', $id)->first();
        if(!$category || $category->meals->count() >0){
            return response()->json([
                'error' => 'can\'t delete this resources'
            ], 402);
        }
        $category->delete();
        return response()->json(['success' => 'category delete successfully'], 200);
    }
}
