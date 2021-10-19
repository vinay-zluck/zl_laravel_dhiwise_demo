<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class AssignPermissionsToRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
         $userPermission = Permission::whereIn('name', [
                'create_user',
                'read_user',
                'update_user',
                'delete_user',
                ])->get();

        /** @var  Role $userRole */
        $userRole = Role::where('name', 'User')->first();
        $userRole->givePermissionTo($userPermission);
 
        $adminPermission = Permission::whereIn('name', [
                'create_user',
                'read_user',
                'update_user',
                'delete_user',
                'manage_roles'])->get();

        /** @var  Role $adminRole */
        $adminRole = Role::where('name', 'Admin')->first();
        $adminRole->givePermissionTo($adminPermission);
    }
}
