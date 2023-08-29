<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\GlobalMethods;
use App\Http\Controllers\Utils\SearcheableMethods;
use App\Http\Requests\BeveurageRequestForm;
use App\Models\Article;
use App\Models\Boisson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Vonage\Client;
use Vonage\SMS\Message\SMS;
use Vonage\Client\Credentials\Basic;

class BeveurageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statut = $request->query('statut');
        $is_alcoholic = $request->query('is_alcoholic');
        $data = SearcheableMethods::beveurage($request, $statut, $is_alcoholic);

        return response()->json($data, 200);

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(BeveurageRequestForm $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), $request->rules());

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

                $beveurage = new Boisson();
                $beveurage->BEV_IS_ALCOHOLIC = $validatedData["BEV_IS_ALCOHOLIC"];
                $beveurage->BEV_DEGREE_ALCOHOLIC = $validatedData["BEV_DEGREE_ALCOHOLIC"];
                $beveurage->BEV_CREATED_AT = GlobalMethods::setTimeZone();
                $beveurage->save();

                // Associate the Beveurage with the Article
                $article->beveurage()->save($beveurage);

                DB::commit();
                return response()->json([
                    'success' => 'Beveurage created successfully.',
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
            $beveurage = $article->beveurage;
            // Retrieve the associated image
            $image = $beveurage->image;

            return response()->json([
                'success' => 'Article and associated Beveurage deleted successfully.',
                'beveurage' => $article,
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
    public function update(BeveurageRequestForm $request, string $id)
    {
        // Find the Article by its ID
        $article = Article::where('ART_ID_ARTICLE', $id)->first();
        if(!is_null($article)) {
            $beveurage = $article->beveurage;
            // Validate the request data
            $validator = Validator::make($request->all(), $request->rules());

            // Check if the validation fails
            if (!$validator->fails()) {

                try {
                    DB::beginTransaction();
                    // If the validation passes, proceed with the registration logic
                    // Retrieve the validated data from the request

                    $validatedData = $validator->validated();
                    // $article = new Article();
                    $article->ART_NAME = $validatedData["ART_NAME"];
                    $article->ART_PRICE = $validatedData["ART_PRICE"];
                    $article->ART_DESCRIPTION = $validatedData["ART_DESCRIPTION"];
                    $article->ART_QUANTITY = $validatedData["ART_QUANTITY"];
                    //If the quantity is superior to zero the statut will be "in-stock" else "in-breck"
                    $article->STA_ID_STATUT = ((int) $validatedData["ART_QUANTITY"] > 0)? 5: 4;
                    $article->save();

                    // $beveurage = new Boisson();
                    $beveurage->BEV_IS_ALCOHOLIC = $validatedData["BEV_IS_ALCOHOLIC"];
                    $beveurage->BEV_DEGREE_ALCOHOLIC = $validatedData["BEV_DEGREE_ALCOHOLIC"];
                    $beveurage->BEV_UPDATED_AT = GlobalMethods::setTimeZone();
                    $beveurage->save();

                    // Associate the Beveurage with the Article
                    $article->beveurage()->save($beveurage);

                    DB::commit();
                    return response()->json([
                        'success' => 'Beveurage updated successfully.',
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
        } else{
            return response()->json([
                'error' => "Article not found",
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the Article by its ID
            $article = Article::where('ART_ID_ARTICLE', $id)->first();
            $storageName ='images/beveurages';
            // Retrieve the associated Beveurage, if available
            $beveurage = $article->beveurage;

            // If there was an associated Beveurage, delete it as well
            if ($beveurage) {
                // Delete related images
                $image = $beveurage->image;
                if($image){
                    $existingImagePath = public_path($storageName . '/' . basename($image->IMG_PATH));

                    if (File::exists($existingImagePath)) {
                        File::delete($existingImagePath);
                    }
                    $image->delete();
                }
                $beveurage->delete();
            }
            // Perform the delete operation on the Article
            $article->delete();

            return response()->json([
                'success' => 'Article and associated Beveurage deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Impossible to delete image. Try again later',
            ], 422);
        }
    }
}
