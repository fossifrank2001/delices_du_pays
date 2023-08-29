<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\GlobalMethods;
use App\Models\Access;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $authId = Auth::user()->CTE_ID_COMPTE;
        $roleId = (int) $request->header('roleId');
//        return [$authId, $roleId];
        /** @var Access $access */
        $access = Access::where('CTE_ID_COMPTE', $authId)
            ->where('ROL_ID_ROLE', $roleId)
            ->first();
        $nb_admin=0;
        $nb_deliver=0;
        $nb_customer=0;
        $nb_chef=0;
        $nb_pending_deliveries=0;
        $nb_canceled_deliveries=0;
        $nb_delivered_deliveries=0;
        $nb_order= 0;
        $nb_reservation= 0;
        if(is_null($access)){
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }else{
            if ($roleId == 1) {//if the user has an admin role
                $admins     = Access::where(['ROL_ID_ROLE'=> 1])->get();
                $chefs      = Access::where(['ROL_ID_ROLE'=> 2])->get();
                $delivers   = Access::where(['ROL_ID_ROLE'=> 3])->get();
                $customers  = Access::where(['ROL_ID_ROLE'=> 4])->get();
//                $reservations = Reservation::All();
//                $orders = Order::All();
                if($admins){
                    $nb_admin = $admins->count();
                }
                if($delivers){
                    $nb_deliver = $delivers->count();
                }
                if($chefs){
                    $nb_chef = $chefs->count();
                }if($customers){
                    $nb_customer = $customers->count();
                }
                return response()->json([
                    "success"=>'Dashboard succesffully fitted',
                    "data"=>[
                        'title'=>"Compte par profil",
                        'admin' => [
                            'text' => 'Admins',
                            "value" => $nb_admin,
                            "url" => '#',
                            "color" =>"dark",
                            'icon'=>"settings"
                        ],
                        'deliver'=>  [
                            'text' => 'Delivers',
                            "value" => $nb_deliver,
                            "url" => '#',
                            "color" =>"success",
                            'icon'=>"contacts"
                        ],
                        'chef'=>  [
                            'text' => 'Chefs',
                            "value" => $nb_chef,
                            "url" => '#',
                            "color" =>"danger",
                            'icon'=>"chef"
                        ],
                        'customer'=>  [
                            'text' => 'Customers',
                            "value" => $nb_customer,
                            "url" => '#',
                            "color" =>"warning",
                            'icon'=>"customer"
                        ],
                        'delivery'=>  [
                            'text' => 'Deliveries',
                            "value" => $nb_order,
                            "url" => '/deliveries/index',
                            "color" =>"info",
                            'icon'=>"delivery_dining"
                        ],
                        'reservation'=>  [
                            'text' => 'Reservations',
                            "value" => $nb_reservation,
                            "url" => '/reservations/index',
                            "color" =>"warning",
                            'icon'=>"book_online"
                        ],
                    ],
                ], 200);
            }
            if($roleId == 3) {
//                $pending_deliveries = Delivery::where('STA_ID_STATUT', 5)->where('CTE_ID_COMPTE', $authId)->get();
//                $canceled_deliveries = Delivery::where('STA_ID_STATUT', 6)->where('CTE_ID_COMPTE', $authId)->get();
//                $delivered_deliveries = Delivery::where('STA_ID_STATUT', 7)->where('CTE_ID_COMPTE', $authId)->get();
                return response()->json([
                    "success"=>'Dashboard succesffully fitted',
                    "data"=>[
                        'title'=>"Deliveries by account",
                        'in_preparation' => [
                            'text' => 'In Preparation',
                            "value" => $nb_pending_deliveries,
                            "url" =>  '/deliveries?statut=5',
                            "color" =>"dark",
                            'icon'=>"delivery_dining"
                        ],
                        'order_cancelled'=>  [
                            'text' => 'Order Cancelled',
                            "value" => $nb_canceled_deliveries,
                            "url" => '/deliveries?statut=6',
                            "color" =>"warning",
                            'icon'=>"delivery_dining"
                        ],
                        'order_delivered'=>  [
                            'text' => 'Order Delivered',
                            "value" => $nb_delivered_deliveries,
                            "url" => '/deliveries?statut=7',
                            "color" =>"success",
                            'icon'=>"delivery_dining"
                        ],
                    ],
                ], 200);
            }
            if($roleId == 3) {
                return response()->json([
                    "success"=>'Dashboard succesffully fitted',
                    "data"=>[
                        'title'=>"Orders by account",
                    ]
                ], 200);
            }
        }
    }

    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function getMenuAndPermissions(Role $role){
        $habilitations = $role->load('permissions', 'menus');
        return response()->json([
            'habilitations' => $habilitations
        ],200);
    }
}
