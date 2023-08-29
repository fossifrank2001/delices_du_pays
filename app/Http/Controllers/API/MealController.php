<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\GlobalMethods;
use App\Http\Controllers\Utils\SearcheableMethods;
use App\Http\Requests\MealRequestForm;
use App\Models\Article;
use App\Models\Repas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Validator;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statut = $request->query('statut');
        $categoryId = $request->query('category');
        $data = SearcheableMethods::meal($request, $statut, $categoryId);

        return response()->json($data, 200);
    }

    public function store(MealRequestForm $request)
    {
        // Validate the request data
        $validator = FacadesValidator::make($request->all(), $request->rules());

        // Check if the validation fails
        if (!$validator->fails()) {

            try {
                DB::beginTransaction();
                // If the validation passes, proceed with the registration logic
                // Retrieve the validated data from the request

                $validatedData = $validator->validated();
                $article = new Article();
                $article->ART_NAME = $validatedData["ART_NAME"];
                $article->ART_PRICE = $validatedData["ART_PRICE"];
                $article->ART_DESCRIPTION = $validatedData["ART_DESCRIPTION"];
                $article->ART_QUANTITY = $validatedData["ART_QUANTITY"];
                //If the quantity is superior to zero the statut will be "in-stock" else "in-breck"
                $article->STA_ID_STATUT = ((int) $validatedData["ART_QUANTITY"] > 0)? 5: 4;
                $article->save();

                $categoriesArray =$validatedData["CATEGORIES"];
                // return $categoriesArray;
                $meal = new Repas();
                $meal->MEL_IN_PROMOTION = $validatedData["MEL_IN_PROMOTION"];
                $meal->MEL_REDUCTION = $validatedData["MEL_REDUCTION"];
                $meal->MEL_CREATED_AT = GlobalMethods::setTimeZone();
                $meal->save();
                // Associate the categories to a meal
                $meal->categories()->sync($categoriesArray);
                // Associate the meal with the Article
                $article->meal()->save($meal);

                DB::commit();
                return response()->json([
                    'success' => 'Meal created successfully.',
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], 422);
                DB::rollBack();
            }
        }
        else{
            // Return the validation errors
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Find the Article by its ID
            $article = Article::where('ART_ID_ARTICLE', $id)->first();

            // Retrieve the associated Beveurage
            $meal = $article->meal;
            // Retrieve the associated images
            $images = $meal->images;
            // Retrieve the associated categories
            $categories = $meal->categories;

            return response()->json([
                'success' => 'Article and associated Beveurage deleted successfully.',
                'meal' => $article,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(MealRequestForm $request, string $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), $request->rules());

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // If the validation passes, retrieve the validated data from the request
        $validatedData = $validator->validated();

        // Find the Article by its ID(
        $article = Article::where('ART_ID_ARTICLE', $id)->first();

        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        // Update the Article attributes
        $article->ART_NAME = $validatedData['ART_NAME'];
        $article->ART_PRICE = $validatedData['ART_PRICE'];
        $article->ART_DESCRIPTION = $validatedData['ART_DESCRIPTION'];
        $article->ART_QUANTITY = $validatedData['ART_QUANTITY'];
        $article->STA_ID_STATUT = ((int) $validatedData['ART_QUANTITY'] > 0) ? 5 : 4;
        $article->save();

        // Find the Meal model associated with the Article
        $meal = $article->meal;

        // Update the Meal attributes
        $meal->MEL_IN_PROMOTION = $validatedData['MEL_IN_PROMOTION'];
        $meal->MEL_REDUCTION = $validatedData['MEL_REDUCTION'];
        $meal->MEL_UPDATED_AT = GlobalMethods::setTimeZone();

        $meal->save();
        // Update the associated categories
        $categoriesArray = $validatedData['CATEGORIES'];
        $meal->categories()->sync($categoriesArray);

            DB::commit();
            return response()->json([
                'success' => 'Meal updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::where('ART_ID_ARTICLE', $id)->first();
        $storageName ='images/meals';
        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        $meal = $article->meal;

        if ($meal) {
            // Delete related images
            $images = $meal->images;
            // return $images;
            if($images){
                foreach ($images as $image) {
                    $existingImagePath = public_path($storageName . '/' . basename($image->IMG_PATH));

                    if (File::exists($existingImagePath)) {
                        File::delete($existingImagePath);
                    }
                    $image->delete();
                }
            }
            // Detach all related categories
            $meal->categories()->detach();

            // Delete the meal
            $meal->delete();
        }

        // Delete the article
        $article->delete();

        return response()->json(['success' => 'Article and related meal deleted successfully'], 200);
    }
}
