<?php

use Illuminate\Database\Seeder;

use Properos\Users\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRole;

class RolesPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (count(Role::all()) == 0) {
            $role_admin = Role::updateOrCreate([
                'name' => 'admin',
                'url' => '/admin/dashboard',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $role_manager = Role::updateOrCreate([
                'name' => 'manager',
                'url' => '/manager/dashboard',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $permission_create_item = Permission::updateOrCreate([
                'name' => 'create items',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $permission_edit_item = Permission::updateOrCreate([
                'name' => 'edit items',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $permission_delete_item = Permission::updateOrCreate([
                'name' => 'delete items',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $permission_create_user = Permission::updateOrCreate([
                'name' => 'create users',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $permission_edit_user = Permission::updateOrCreate([
                'name' => 'edit users',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $permission_delete_user = Permission::updateOrCreate([
                'name' => 'delete users',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $role_admin->givePermissionTo($permission_create_item);
            $role_manager->givePermissionTo($permission_create_item);
            /* $permission_create_item->assignRole($role_admin);
             */
            $role_admin->givePermissionTo($permission_edit_item);
            $role_manager->givePermissionTo($permission_edit_item);
           /*  $permission_edit_item->assignRole($role_admin); */

            $role_admin->givePermissionTo($permission_delete_item);
            $role_manager->givePermissionTo($permission_delete_item);
            /* $permission_delete_item->assignRole($role_admin); */

            $role_admin->givePermissionTo($permission_create_user);
            /* $permission_create_user->assignRole($role_admin); */

            $role_admin->givePermissionTo($permission_edit_user);
           /*  $permission_edit_user->assignRole($role_admin); */

            $role_admin->givePermissionTo($permission_delete_user);
            /* $permission_delete_user->assignRole($role_admin); */
        }
    }
}
