<?php
namespace Database\Seeders;

use App\Http\Controllers\Utils\GlobalMethods;
use App\Models\Access;
use App\Models\Role;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Import the Hash facade

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'CTE_FIRSTNAME' => 'NOZAKAP FOSSI',
                'CTE_LASTNAME' => 'Frank Jordan',
                'CTE_EMAIL' => 'john.doe@example.com',
                'CTE_PHONE' => '123456789',
                'CTE_TOWN' => 'New York',
                'CTE_QUARTER' => 'Downtown',
                'CTE_PASSWORD' => Hash::make('password123'), // Hash the password
                'CTE_DATECREATE' => now(),
                'EMAIL_VERIFIED_AT' => now(),
            ],
            [
                'CTE_FIRSTNAME' => 'TINKEU MALKO',
                'CTE_LASTNAME' => 'Dyran',
                'CTE_EMAIL' => 'jane.smith@example.com',
                'CTE_PHONE' => '987654321',
                'CTE_TOWN' => 'Los Angeles',
                'CTE_QUARTER' => 'Beverly Hills',
                'CTE_PASSWORD' => Hash::make('password456'), // Hash the password
                'CTE_DATECREATE' => now(),
                'EMAIL_VERIFIED_AT' => now(),
            ],
        ];
        // Loop through the array and create user records using the User model
        foreach ($users as $index => $userData) {
            $user = User::create($userData);
            if ($index === 0){
                $access = new Access();
                //Assign first account created
                $access->compte()->associate($user);
                    //Assign admin role
                $role = Role::where('ROL_ID_ROLE' ,$index + 1)->first();
                $access->role()->associate($role);
                    // Assign Activated statut
                $statut = Statut::where('STA_ID_STATUT' ,$index + 1)->first();
                $access->statut()->associate($statut);
                $access->save();

                $user->statut()->associate($statut);
                $user->save();
            }else{
                $statut = Statut::find($index + 1); // Assuming ID 1 is the ID of the first statut
                if ($statut) {
                    $user->statut()->associate($statut);
                    $user->save();
                }
            }
        }



        // Sample first names and last names
        $firstNames = ['John', 'Jane', 'Michael', 'Emily', 'David', 'Sarah', 'Daniel', 'Olivia'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Davis', 'Miller', 'Wilson'];
        // Create 25 accounts with different data
        for ($i = 1; $i <= 25; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];

            $user = User::create([
                'CTE_FIRSTNAME' => $firstName,
                'CTE_LASTNAME' => $lastName,
                'CTE_EMAIL' => "nguetsa.yvan{$i}@example.com",
                'CTE_PHONE' => "98795432{$i}",
                'CTE_TOWN' => 'Los Angeles',
                'CTE_QUARTER' => 'Beverly Hills',
                'CTE_PASSWORD' => Hash::make("password1650{$i}"), // Hash the password
                'CTE_DATECREATE' => now(),
                'EMAIL_VERIFIED_AT' => now(),
            ]);
            $statut = Statut::find($index + 1); // Assuming ID 1 is the ID of the first statut
            if ($statut) {
                $user->statut()->associate($statut);
                $user->save();
            }

            GlobalMethods::createCustomerAccess($user);
        }
    }
}

