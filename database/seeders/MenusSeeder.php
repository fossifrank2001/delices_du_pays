<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $menus = [
            [
                'MEN_LIBELLE' => 'Dashboard',
                'MEN_ICON' => 'dashboard',
                'MEN_URL' => '/admin/dashboard',
                'MEN_GROUP' => 0,
                'MEN_ORDER' => 1,
            ],
            [
                'MEN_LIBELLE' => 'Account',
                'MEN_ICON' => 'account_circle',
                'MEN_URL' => '', // Aucune URL pour le parent "Comptes"
                'MEN_GROUP' => 1,
                'MEN_ORDER' => 2,
            ],
            [
                'MEN_LIBELLE' => 'Roles',
                'MEN_ICON' => 'how_to_reg',
                'MEN_URL' => '', // Aucune URL pour le parent "Comptes"
                'MEN_GROUP' => 1,
                'MEN_ORDER' => 3,
            ],
            [
                'MEN_LIBELLE' => 'Accesses',
                'MEN_ICON' => 'vpn_key',
                'MEN_URL' => '', // Aucune URL pour le parent "Comptes"
                'MEN_GROUP' => 1,
                'MEN_ORDER' => 4,
            ],
            [
                'MEN_LIBELLE' => 'Categories',
                'MEN_ICON' => 'category',
                'MEN_URL' => '', // Aucune URL pour le parent "Comptes"
                'MEN_GROUP' => 1,
                'MEN_ORDER' => 5,
            ],
            [
                'MEN_LIBELLE' => 'Articles',
                'MEN_ICON' => 'restaurant_menu',
                'MEN_URL' => '', // Aucune URL pour le parent "Articles"
                'MEN_GROUP' => 1,
                'MEN_ORDER' => 6,
            ],
            [
                'MEN_LIBELLE' => 'Towns/Quarters',
                'MEN_ICON' => 'location_city',
                'MEN_URL' => '', // Aucune URL pour le parent "Articles"
                'MEN_GROUP' => 1,
                'MEN_ORDER' => 7,
            ],
            [
                'MEN_LIBELLE' => 'Reservations',
                'MEN_ICON' => 'book_online',
                'MEN_URL' => '', // Aucune URL pour le parent "Articles"
                'MEN_GROUP' => 1,
                'MEN_ORDER' => 8,
            ],
            [
                'MEN_LIBELLE' => 'Deliveries',
                'MEN_ICON' => 'delivery_dining',
                'MEN_URL' => '', // Aucune URL pour le parent "Articles"
                'MEN_GROUP' => 1,
                'MEN_ORDER' => 9,
            ],
            [
                'MEN_LIBELLE' => 'Orders',
                'MEN_ICON' => 'fast_food',
                'MEN_URL' => '', // Aucune URL pour le parent "Articles"
                'MEN_GROUP' => 1,
                'MEN_ORDER' => 10,
            ],
        ];

        DB::table('menus')->insert($menus);

        $menusId = Menu::pluck('MEN_ID_MENU')->toArray();
        $roles=Role::get();

        foreach ($roles as $index => $role) {
            switch ($index){
                case 0:
                    $role->menus()->attach($menusId);
                    break;
                case 1:
                    $role->menus()->attach([$menusId[0], $menusId[7], $menusId[9]]);
                    break;
                case 2:
                    $role->menus()->attach([$menusId[0], $menusId[8]]);
                    break;
                case 3:
                    $role->menus()->attach([$menusId[0]]);
                    break;
            }
        }
    }
}
