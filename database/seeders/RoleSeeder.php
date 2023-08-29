<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Only seed if roles table is empty
        if (Role::count() === 0) {
            // Wrap the seeding process in a transaction
            DB::beginTransaction();

            try {
                $roles = [
                    ['ROL_ID_ROLE' => 1, 'ROL_LIBELLE' => 'Admin'],
                    ['ROL_ID_ROLE' => 2, 'ROL_LIBELLE' => 'Chef'],
                    ['ROL_ID_ROLE' => 3, 'ROL_LIBELLE' => 'Deliver'],
                    ['ROL_ID_ROLE' => 4, 'ROL_LIBELLE' => 'Customer'],
                ];
                $permissions = [
                    ['PER_ID_PERMISSION' => 1, 'PER_LIBELLE' => 'CREATE'],
                    ['PER_ID_PERMISSION' => 2, 'PER_LIBELLE' => 'READ'],
                    ['PER_ID_PERMISSION' => 3, 'PER_LIBELLE' => 'UPDATE'],
                    ['PER_ID_PERMISSION' => 4, 'PER_LIBELLE' => 'DELETE'],
                    ['PER_ID_PERMISSION' => 5, 'PER_LIBELLE' => 'EXPORT'],
                    ['PER_ID_PERMISSION' => 6, 'PER_LIBELLE' => 'VALIDATION'],
                    ['PER_ID_PERMISSION' => 7, 'PER_LIBELLE' => 'REJET'],
                    ['PER_ID_PERMISSION' => 8, 'PER_LIBELLE' => 'ANNULATION'],
                    ['PER_ID_PERMISSION' => 9, 'PER_LIBELLE' => 'SOUMISSION'],
                ];

                // Loop through the array and create role records using the Role model
                // Loop through the array and create role records using the Role model
                foreach ($roles as $index => $roleData) {
                    $role = Role::create($roleData);

                    if ($index === 0) {
                        foreach ($permissions as $permission) {
                            $permissionId = DB::table('permissions')->insertGetId($permission);
                            DB::table('permission_role')->insert([
                                'PER_ID_PERMISSION' => $permissionId,
                                'ROL_ID_ROLE' => 1, // ID du rôle d'administration
                            ]);
                        }
                    }
                }
                // Commit the transaction
                DB::commit();
            } catch (\Exception $e) {
                // Rollback the transaction and log the error
                DB::rollback();
                \Log::error("RoleSeeder error: {$e->getMessage()}");
            }
        }
    }
}
