<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Boisson;
use App\Models\Image;
use App\Models\Repas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImageController extends Controller
{

    public function deleteImage(Image $image){
        $imageExist   = $image;
        if($imageExist){
            // Assuming $model contains the model name (e.g., 'User', 'Boisson', 'Meal')
            $model = explode('\\', $imageExist->IMAGEABLE_type)[2];
            $storageName ='images/';
            if ($model === 'User') {
                $storageName .= 'avatars';
            } elseif ($model === 'Boisson') {
                $storageName .= 'beveurages';
            } elseif ($model === 'Repas') {
                $storageName .= 'meals';
            } else {
                // Handle the case where $model doesn't match any known model
                // You can set a default value or raise an error, depending on your requirements
                $storageName .= 'unknown'; // Set a default value, for example
            }

            // Delete the existing path if it exists
            $existingImagePath = public_path($storageName . '/' . basename($imageExist->IMG_PATH));
            // return File::exists($existingImagePath);
            if (File::exists($existingImagePath)) {
                File::delete($existingImagePath);
            }
            $imageExist->delete();
            return response()->json([
                'success' => "Image deleted successfully",
                'image' => null
            ], 200);
        }

        return response()->json(['error' => "Image does\'nt exist"], 500);

    }

    public function createOneImage(Request $request){
        $validator = Validator::make($request->all(),[
            'IMG_PATH' => ['required', 'image', 'mimes:png,gif,jpg,jpeg,bim', 'max:3072'],
            'IMAGEABLE_type' => ['required', 'string'],
            'IMAGEABLE_id' => ['required', 'integer']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $type = $validatedData["IMAGEABLE_type"];
        $id = $validatedData["IMAGEABLE_id"];
        //get the Model name
        $model = explode('\\', $type)[2];
        $storageName ='images/';
        if ($model === 'User') {
            $storageName .= 'avatars';
        } elseif ($model === 'Boisson') {
            $storageName .= 'beveurages';
        } elseif ($model === 'Repas') {
            $storageName .= 'meals';
        } else {
            // Handle the case where $model doesn't match any known model
            // You can set a default value or raise an error, depending on your requirements
            $storageName .= 'unknown'; // Set a default value, for example
        }
        // $storageName = $model ==='User'? 'avatars': 'beveurages';
        //manage file
        $file = $request->file('IMG_PATH');
        $fileName = Str::random(6) .'_'. $file->getClientOriginalName();


        //save the uploaded file to the public directory
        $file->move(public_path($storageName), $fileName);
        //Generate the URL of the saved file
        $fileDoc = $storageName . '/' . $fileName;
        $imageExist = Image::where('IMAGEABLE_type',$type)
                    ->where('IMAGEABLE_id', $id)->first();
        if($imageExist){
            // Delete the existing path if it exists
            $existingImagePath = public_path($storageName . '/' . basename($imageExist->IMG_PATH));
            if (File::exists($existingImagePath)) {
                File::delete($existingImagePath);
            }

            // update the existing image with the new path
            $imageExist->update([
                "IMG_PATH" => $fileDoc,
            ]);
            $image = $imageExist;
        }else{
            $image = Image::create([
                "IMG_PATH" => $fileDoc,
                "IMAGEABLE_type" => $type,
                "IMAGEABLE_id" => $id
            ]);
        }

        if($model === "User"){
            $compte = User::where('CTE_ID_COMPTE', $validatedData["IMAGEABLE_id"])->first();
            $compte->image()->save($image);
        }
        if($model === "Boisson"){
            $beveurage = Boisson::where('BEV_ID_BEVERAGE', $validatedData["IMAGEABLE_id"])->first();
            $beveurage->image()->save($image);
        }
        return response()->json([
            'success' => "Image Successfully upload",
            'image' => $image
        ], 200);

    }

    public function createManyImages(Request $request){
        $validator = Validator::make($request->all(),[
            'IMG_PATH.*' => ['required', 'mimes:png,gif,jpg,jpeg,bim', 'max:3072'],
            'IMAGEABLE_type' => ['required', 'string'],
            'IMAGEABLE_id' => ['required', 'integer']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();
        $type = $validatedData["IMAGEABLE_type"];
        $id = $validatedData["IMAGEABLE_id"];
        //manage file
        $files = $request->file('IMG_PATH');
        //get the Model name
        $model = explode('\\', $type)[2];
        $storageName ='images/';
            if($model === 'Repas') {
                $storageName .= 'meals';
            } else {
                $storageName .= 'unknown'; // Set a default value, for example
            }
             $images =[];
            //  dd($files);
                foreach ($files as $file) {

                    $fileName =  time() . '-' . $file->getClientOriginalName();
                    //save the uploaded file to the public directory
                    $file->move(public_path($storageName), $fileName);
                    //Generate the URL of the saved file
                    $fileDoc = $storageName . '/' . $fileName;

                    $image = Image::create([
                        "IMG_PATH" => $fileDoc,
                        "IMAGEABLE_type" => $type,
                        "IMAGEABLE_id" => $id
                    ]);
                    array_push($images, $image);
                }

            if($model === "Repas"){
                $repas = Repas::where('MEL_ID_MEAL', $validatedData["IMAGEABLE_id"])->first();
                $repas->images()->saveMany($images);
            }
            return response()->json(['success' => "Images Successfully upload"], 200);

    }
}
