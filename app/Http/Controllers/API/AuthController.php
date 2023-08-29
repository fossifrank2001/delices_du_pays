<?php

namespace App\Http\Controllers\API;

use App\Events\SendVerificationEmailEvent;
use App\Http\Controllers\Utils\GlobalMethods;
use App\Models\Access;
use App\Notifications\AdminNotificationForNewAccount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthRequestForm;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Utils\GlobalMethods as UtilsGlobalMethods;
use App\Http\Controllers\Utils\Utils;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

// use Firebase\JWT\JWT;
use Tymon\JWTAuth\Contracts\Providers\JWT;

class AuthController extends Controller
{

    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function register(AuthRequestForm $request)
    {

        // Validate the request data
        $validator = Validator::make($request->all(), $request->rules());

        // Check if the validation fails
        if ($validator->fails()) {
            // Return the validation errors
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        // If the validation passes, proceed with the registration logic
        // Retrieve the validated data from the request
        $validatedData = $validator->validated();
        // Vérifier si un compte existe déjà avec le numéro de téléphone
        $compte_tel = User::where('CTE_PHONE', $validatedData['CTE_PHONE'])->first();


            // Vérifier si un compte existe déjà avec l'adresse email
            $compte_email = User::where('CTE_EMAIL', $validatedData['CTE_EMAIL'])->first();
            if ($compte_tel) {
                return response()->json([
                    'error' => 'An account already exist with this phone numbber',
                ], 400);
            } elseif ($compte_email) {
                return response()->json([
                    'error' => 'An account already exist with this email adress',
                ], 400);
            } else {
                // Create a new Compte instance and populate it with the validated data
                try {
                    DB::beginTransaction();
                    // Generate an unique token for email verification
                    $verificationToken = Str::random(60);
                    // Check if the 'STA_ID_STATUT' is present in the $validatedData array
                    $compte = User::create([
                        'CTE_FIRSTNAME' =>$validatedData['CTE_FIRSTNAME'],
                        'CTE_LASTNAME'=>$validatedData['CTE_LASTNAME'],
                        'CTE_EMAIL' =>$validatedData['CTE_EMAIL'],
                        'CTE_PHONE' =>$validatedData['CTE_PHONE'],
                        'CTE_TOWN' =>$validatedData['CTE_TOWN'],
                        'CTE_QUARTER' =>$validatedData['CTE_QUARTER'],
                        'CTE_PASSWORD'=>Hash::make($validatedData['CTE_PASSWORD']),
                        'CTE_DATECREATE'=>UtilsGlobalMethods::setTimeZone(),
                        'CTE_TOKEN' =>$verificationToken
                    ]);

                    $defaultStatut = 1;//activated
                    // Create a new statut (if you want to) or get an existing one based on the STA_ID_STATUT.
                    $statut = Statut::where(['STA_ID_STATUT' => $defaultStatut])->first();

                    if (isset($compte)) {
                        try {
                            // TODO Associate the user account with the statut
                            $compte->STA_ID_STATUT=$defaultStatut;
                            $compte->statut()->associate($statut);

                            //TODO Déclencher l'événement pour envoyer l'e-mail de vérification
                            event(new SendVerificationEmailEvent($compte, $verificationToken));

                            //TODO assign a default access(Customer) to the user
                            GlobalMethods::createCustomerAccess($compte);

                            //TODO Notifier l'amin d un nouveeau compte creer nessecaisre a activer

                            $admin = GlobalMethods::getAdminsCompte()->first();
                            $admin->notify(new AdminNotificationForNewAccount($compte));

                            DB::commit();
                            return response()->json([
                                'success' => 'Account Successfully created.An email was sent to your email adress, Please check it to verify your email for more security',
                            ], 201);
                        }
                        catch (\Exception $e) {
                            return response()->json([
                                'error' => 'An error occur when sending an email, try again later',
                            ], 500);
                            DB::rollBack();
                        }
                    }else {
                        return response()->json([
                            'error' => 'An error occur when creating the account, try again later',
                        ], 500);
                    }
                }catch (\Exception $e) {
                    // Log::error('Error occurred while setting the time zone: ' . $e->getMessage());
                    return response()->json([
                        'error' => 'An error occur when creating the account, try again later',
                    ], 500);
                }
            }
    }

    public function verifyEmail($token)
    {

        $verification_token =$token;
        $compte = User::where('CTE_TOKEN', $verification_token)->first();

        if ($compte) {
            $compte->update([
                'EMAIL_VERIFIED_AT' => UtilsGlobalMethods::setTimeZone(), // Marquer l'utilisateur comme vérifié
                'CTE_TOKEN' => null, // Effacer le jeton de vérification après vérification
            ]);

            // Return a JSON response to the frontend indicating successful verification
            return redirect(Utils::$URL_FRONT.'/login?success=Email sucessfully verified', 201);
        } else {
            // Return a JSON response to the frontend indicating an error
            return response()->json(['success' => 'Invalid verification token'], 400);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('CTE_LOGIN', 'CTE_PASSWORD');
        $remember = $request->has('REMENBER_ME'); // Check if "Remember Me" checkbox is checked

        $validator = Validator::make($credentials, [
            'CTE_LOGIN' => ['required'],
            'CTE_PASSWORD' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toArray()], 422);
        }
        // Find the user by username (email or phone)
        $validatedData = $validator->validated();
        $compte = User::findByUsername($validatedData['CTE_LOGIN'])['account'];
        $column = User::findByUsername($validatedData['CTE_LOGIN'])['column'];
        // Change 'CTE_LOGIN' key to the corresponding $column name key
        $validatedData[$column] = $validatedData['CTE_LOGIN'];
        unset($validatedData['CTE_LOGIN']);

        if (!$compte || !$compte->validatePassword($validatedData['CTE_PASSWORD'])) {
            // Authentication failed, return an error response or redirect back to the login page
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
//        JWTAuth::invalidate(JWTAuth::getToken());
        $token = self::tokenCreator($compte);
        // Update the user's remember token if "Remember Me" is checked
        if ($remember) {
            $compte->updateRememberToken($token);
        }
        // Authentication successful, return the token along with compte information
        $compte = $compte->load(['accesses.role', 'notifications', 'statut']);
        $image = $compte->image;
        return response()->json(array_merge($this->respondWithToken($token), [
            'success' => "Successfully logged",
            'compte' => $compte,
        ]), 200);
    }


    protected static function tokenCreator($user)
    {
        // Create the token for the user using the `JWTAuth` facade
        $token = JWTAuth::fromUser($user);

        return $token;
    }


    protected function respondWithToken(String $token):array
    {
        // Calculate the token expiration time in seconds based on the "Remember Me" setting
        $tokenExpiration = $this->getJwtTtl();

        return [
            'ACCESS_TOKEN' => $token,
            'TOKEN_TYPE' => 'bearer',
            'EXPIRES_IN' => $tokenExpiration,
        ];
    }

    // Helper method to get JWT token expiration based on the "Remember Me" setting
    protected function getJwtTtl()
    {
        $ttl = config('jwt.ttl');
        // return request('REMENBER_ME');
        // If "Remember Me" is checked, set the token expiration to a longer duration
        if (request('REMENBER_ME')) {
            $rememberTtl = config('jwt.remember_ttl', 525600); // Default: 1 year (60 minutes * 24 hours * 365 days)
            return $rememberTtl * 60; // Convert minutes to seconds
        }

        return $ttl * 60; // Convert minutes to seconds for regular TTL
    }

    public function logout()
    {
        try {
            // Get the currently authenticated user
            $compte = Auth::user();

            // Invalidate (blacklist) the current token to ensure it cannot be used again
            JWTAuth::invalidate(JWTAuth::getToken());

            // Logout the user
            Auth::logout();
            return response()->json(["success" => 'Successfully deleted'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "An error occur.Please try again later"
            ], 500);
        }
    }



    public function refresh()
    {
        try {
            // Refresh the token
            $newToken = auth()->refresh();
            return response()->json([
                'success' => 'Your token has been updated',
                'token' => $this->respondWithToken($newToken)],
                200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
