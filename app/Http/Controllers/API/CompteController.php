<?php

namespace App\Http\Controllers\API;

use App\Events\ResetPasswordEmailEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\GlobalMethods;
use App\Http\Controllers\Utils\SearcheableMethods;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\AccountRequestForm;
use App\Http\Requests\CompteRequest;
use App\Models\Access;
use App\Models\PasswordReset;
use App\Models\Statut;
use App\Models\User;
use App\Notifications\AccountCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statut = $request->query('statut');
        $data = SearcheableMethods::account($request, $statut);

        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(AccountRequestForm $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), $request->rules());

        // Check if the validation fails
        if (!$validator->fails()) {

            // If the validation passes, proceed with the registration logic
            // Retrieve the validated data from the request
            $validatedData = $validator->validated();
            $compte_tel = User::where('CTE_PHONE', $validatedData['CTE_PHONE'])->first();

            // Vérifier si un compte existe déjà avec l'adresse email
            $compte_email = User::where('CTE_EMAIL', $validatedData['CTE_EMAIL'])->first();
            if ($compte_tel) {
                return response()->json([
                    'error' => 'An account already exists with this phone number.',
                ], 400);
            } elseif ($compte_email) {
                return response()->json([
                    'error' => 'An account already exists with this email address.',
                ], 400);
            } else {
                try {
                    DB::beginTransaction();
                    $password = GlobalMethods::getRamdomPassword();
                    // $passwordCrypter = ;
                    /** @var User $account */
                    $account = User::create([
                        // 'STA_ID_STATUT' => $validatedData['STA_ID_STATUT'],
                        'CTE_FIRSTNAME'=> $validatedData['CTE_FIRSTNAME'],
                        'CTE_LASTNAME'=> $validatedData['CTE_LASTNAME'],
                        'CTE_EMAIL' => $validatedData['CTE_EMAIL'],
                        'CTE_PHONE'=> $validatedData['CTE_PHONE'],
                        'CTE_TOWN' => $validatedData['CTE_TOWN'],
                        'CTE_QUARTER'=> $validatedData['CTE_QUARTER'],
                        'CTE_PASSWORD' => Hash::make($password),
                        'CTE_DATECREATE'=> GlobalMethods::setTimeZone(),
                    ]);
                    $statut = Statut::where([
                        'STA_ID_STATUT' =>$validatedData['STA_ID_STATUT']
                    ])->first();
                    if(isset($account)){
                        try {
                            // Fire a notification for the created user
                            $account->notify(new AccountCreatedNotification($account, $password));
                            $account->statut()->associate($statut);
                            DB::commit();
                            return response()->json([
                                'success' => 'Account Successfully created.',
                                'account' => $account
                            ], 201);
                        } catch (\Exception $e) {
                            return response()->json([
                                'error' => $e->getMessage(),
                            ], 500);
                            DB::rollBack();
                        }
                    }else{
                        return response()->json([
                            'error' => 'An error occur while creating the account. Try again later',
                        ], 500);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'error' => $e->getMessage(),
                    ], 500);
                }
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

            $tab = [];
            $compte = User::where('CTE_ID_COMPTE', $id)->first();
            $tab["CTE_LOGIN"] = $compte->CTE_LOGIN;
            $tab["CTE_FIRSTNAME"] = $compte->CTE_FIRSTNAME;
            $tab["CTE_LASTNAME"] = $compte->CTE_LASTNAME;
            $tab["CTE_EMAIL"] = $compte->CTE_EMAIL;
            $tab["CTE_PHONE"] = $compte->CTE_PHONE;
            $tab["CTE_DATECREATE"] = $compte->CTE_DATECREATE;
            $tab["CTE_DATEUPDATE"] = $compte->CTE_DATEUPDATE;
            $tab["STATUS"] = $compte->statut->STA_LIBELLE ;
            $tab["IMGAGE"] = $compte->image->IMG_PATH ;
            return response()->json([
                'success' => 'Account successfully read',
                'compte' =>$tab
            ], 200);
        } catch (\Exception $exception) {
            // En cas d'erreur lors de la suppression
            return response()->json([
                'error' => 'Une erreur est survenue durant la lecture (Utilisateur non existant). Veuillez réessayer plus tard',
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AccountRequestForm $request, string $id)
    {
        $validatedData = $request->validated();
        $account = User::where('CTE_ID_COMPTE', $id)->first();
        $account->update([
            'CTE_FIRSTNAME'=> $validatedData['CTE_FIRSTNAME'],
            'CTE_LASTNAME'=> $validatedData['CTE_LASTNAME'],
            'CTE_EMAIL' => $validatedData['CTE_EMAIL'],
            'CTE_PHONE'=> $validatedData['CTE_PHONE'],
            'CTE_TOWN' => $validatedData['CTE_TOWN'],
            'CTE_QUARTER'=> $validatedData['CTE_QUARTER'],
            'CTE_DATEUPDATE'=> GlobalMethods::setTimeZone()->format('d/m/Y H:i:s'),
        ]);
        $statut = Statut::where([
            'STA_ID_STATUT' =>$validatedData['STA_ID_STATUT']
        ])->first();
        $account->statut()->associate($statut);
        return response()->json([
            'success' => 'Account Successfully updated.',
            'account' => $account
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            /** @var User $compte */
            $compte = User::where('CTE_ID_COMPTE', $id)->first();

            // Vérifier s'il y a des accès liés
            if ($compte->accesses->count() > 0) {
                // Retourner une réponse JSON avec un message d'erreur
                return response()->json([
                    'error' => 'You can delete an account linked to an access',
                ], 400);
            }
            // Supprimer le modèle de compte
            $compte->delete();

            // Retourner une réponse JSON avec un message de succès
            return response()->json([
                'success' => 'Account successfully deleted',
            ], 200);
        } catch (\Exception $exception) {
            // En cas d'erreur lors de la suppression
            return response()->json([
                'error' => 'Une erreur est survenue durant la suppression. Veuillez réessayer plus tard',
            ], 500);
        }
    }

    public function forgotPassword(Request $request){
        try {
            $validator = Validator::make($request->all(), ['CTE_EMAIL'=>['required', 'email']]);
            if($validator->fails()){
                return response()->json([
                    'error' => $validator->errors()->toArray()
                ], 422);
            }
            $comptes = User::where('CTE_EMAIL', $request->CTE_EMAIL)->get();

            if(count($comptes) >0){
                try {
                    DB::beginTransaction();
                    $token = Str::random(64);
                    event(new ResetPasswordEmailEvent($token, $request->CTE_EMAIL));
                    DB::commit();
                    $passwordReset = PasswordReset::where('PAS_RES_EMAIL', $request->CTE_EMAIL)->first();
                    if($passwordReset){
                        $passwordReset->PAS_RES_TOKEN = $token;
                        $passwordReset->save();
                    }else{
                        PasswordReset::create([
                            'PAS_RES_EMAIL'=> $request->CTE_EMAIL,
                            'PAS_RES_TOKEN'=> $token,
                            'PAS_RES_CREATED_AT' => GlobalMethods::setTimeZone()
                        ]);
                    }
                    // return $pass;
                    return response()->json([
                        'success' => 'Pleace check your email to reset your password'
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'error' => 'Something wromg when sending you an email, try again later.'
                    ], 400);
                    DB::rollBack();
                }
            }else{
                return response()->json([
                    'error' => 'User not found. Wrong Email'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(String $token){
        //Find the reset data based on the provided token
        $resetData = PasswordReset::where('PAS_RES_TOKEN', $token)->first();
//        return $resetData;
        if($resetData){

            // Retrieve the user account based on the email in the reset data
            $compte = User::where('CTE_EMAIL', $resetData->PAS_RES_EMAIL)->first();
            if ($compte) {
                // Encrypt the user's ID using the Laravel Crypt facade
                $token = Str::random(64);
                $compte->CTE_TOKEN= $token;
                $compte->save();
                // Redirect the user to the reset password page on the frontend with the token
                return redirect(Utils::$URL_FRONT_BASE . 'auth/password-reset?token=' .  $token);
            } else {
                // User account not found for the email in the reset data
                return response()->json(['error' => 'User not found for the provided email'], 404);
            }
        }else{
            // Invalid or expired token
            return  redirect(Utils::$URL_FRONT_BASE . 'web/activated?error=Invalid or expired token');
        }
    }

    public function resetNewPassword(Request $request){
        $validator = Validator::make($request->all(), [
            "id" => ['required', 'string'],
            'CTE_PASSWORD' => ['required', 'string', 'confirmed', 'min:6']
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $compte= User::where('CTE_TOKEN', $validatedData['id'])->first();
//        return $compte;
        if (!$compte){
            return response()->json(['error' => 'An error Occur, Invalid token provided.'], 400);
        }
        $compte->update([
            "CTE_PASSWORD" => Hash::make($validatedData['CTE_PASSWORD'])
        ]);
        $record = PasswordReset::where('PAS_RES_EMAIL', $compte->CTE_EMAIL)->first();
        if (!$record){
            return response()->json(['error' => 'An error Occur when updating your password.'], 401);
        }
        $record->delete();
        return response()->json(['success' => 'Your password has been successfully reset'], 200);

    }
    public function activateCompte(User $account, $fromEmailIbox)
    {
        $account->STA_ID_STATUT = 1;
        $account->save();
        $statut = Statut::where('STA_ID_STATUT', 1)->first();
        $account->statut()->associate($statut);

        $access = Access::where('CTE_ID_COMPTE', $account->CTE_ID_COMPTE)->first();
        $access->STA_ID_STATUT = 1;
        $access->save();
        $admin = GlobalMethods::getAdminsCompte()->first();

        $notification = $admin->notifications->first(function ($notification) use ($account) {
            $notificationAccount = $notification['data']['account'];
            return (int) $notificationAccount['CTE_ID_COMPTE'] === (int) $account->CTE_ID_COMPTE;
        });

        if ($notification) {
            $notification->delete(); // Delete the found notification
        }
        if ((int) $fromEmailIbox=== 1){
            return redirect(Utils::$URL_FRONT_BASE . 'web/activated?name='.$account->getFullname());
        }else{
            return response()->json(['success' => 'Activated Successfully'],200);
        }
    }

    public function deactivateCompte(User $account)
    {
        $statut = Statut::where('STA_ID_STATUT', 2)->first();
        $account->STA_ID_STATUT = 2;
        $account->save();
        $account->statut()->associate($statut);

        return response()->json(['success' => 'Deactivated Successfully'],200);

    }

}
