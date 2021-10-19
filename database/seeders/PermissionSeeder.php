<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $permissions = [
            'create_contact_group',
            'read_contact_group',
            'update_contact_group',
            'delete_contact_group',
            'create_group',
            'read_group',
            'update_group',
            'delete_group',
            'create_contact',
            'read_contact',
            'update_contact',
            'delete_contact',
            'create_user',
            'read_user',
            'update_user',
            'delete_user',
            'manage_roles'
];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
