<?php
namespace App\Http\Controllers\Utils;

use App\Models\Boisson;
use App\Models\Repas;
use App\Models\Role;
use App\Models\Statut;
use DateTime;
use Exception;
use DateTimeZone;
use App\Models\Access;
use App\Models\Compte;
use App\Models\Autorisation;
use App\Models\Habilitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

// ...

// Utilisation de la classe DateTime et DateTimeZone
$datetime = new DateTime('now', new DateTimeZone('Africa/Douala'));
// ...

class GlobalMethods
{

    public static function checkUserAccesses(int $roleId, int $authId):Access | JsonResponse
    {
        $accesses = null;
        try {
            // Check user identity
            $accesses = Access::where('ROL_ID_ROLE',$roleId)->where('CTE_ID_COMPTE', $authId)->get();

            return $accesses;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //make a random password using the generator RAND
    public static function getRamdomPassword($length = 6)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

    public static function setTimeZone()
    {
        try {
            $timezone = new DateTimeZone('Africa/Douala');
            $datetime = new DateTime('now', $timezone);
        } catch (\Exception $e) {
            $datetime = new DateTime('now');
        }

        return $datetime;
    }

    //Retrieve the model via type typed and his related id
    public static function retrieveModels(String $type, String $id): ?Model {
        switch ($type) {
            case 'Boisson':
                return Boisson::where('BEV_ID_BEVERAGE', $id)->first();
            case 'Repas':
                return Repas::where('MEL_ID_MEAL', $id)->first();
            default:
                return null; // Return null for other cases
        }
    }

    /**
     * get the admins compte
     * @return Collection|Array
     */
    public static function getAdminsCompte()
	{

		$comptes = Access::with('compte')
            ->where(['ROL_ID_ROLE' => 1])
            ->where(['STA_ID_STATUT' => 1])
            ->get()
            ->pluck('compte');;
		return $comptes;
	}

    /**
     * create a default access (Customer to the user) to an account provided
     * @param User $account
     * @return Access|null
     */
    public static function createCustomerAccess(User $account): void
    {
        $statut = Statut::where('STA_ID_STATUT', 2)->first();
        $role = Role::where('ROL_ID_ROLE', 4)->first();

        $access = new Access();
        $access->compte()->associate($account);
        $access->statut()->associate($statut);
        $access->role()->associate($role);
        $access->save();
    }
}
