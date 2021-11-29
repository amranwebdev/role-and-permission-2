<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create role
        $SuperAdminrole = Role::create(['name' => 'Superadmin']);
        $Adminrole = Role::create(['name' => 'Admin']);
        $Userrole = Role::create(['name' => 'User']);


        //permission as array
        $permissions = [

            [
                'group_name' =>'dashboard',
                'permissions' => [
                    'dashboard-view',
                    'dashboard-edit',
                ]
            ],

            [
                'group_name' =>'admin',
                'permissions' => [
                    'admin-create',
                    'admin-view',
                    'admin-edit',
                    'admin-delete',
                ]
            ],

            [
                'group_name' =>'blog',
                'permissions' => [
                    'blog-create',
                    'blog-view',
                    'blog-edit',
                    'blog-delete',
                ]
            ],
            [
                'group_name' =>'role',
                'permissions' => [
                    'role-list',
                    'role-create',
                    'role-edit',
                    'role-delete',
                ]
            ],
            [
                'group_name' =>'profile',
                'permissions' => [
                    'profile-view',
                    'profile-edit',
                ]
            ],
         ];

        //assign permissions
        for($i = 0; $i < count($permissions); $i++){
            $permissionGroup = $permissions[$i]['group_name'];
            for($j = 0; $j < count($permissions[$i]['permissions']); $j++){
                $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup]);
                $SuperAdminrole->givePermissionTo($permission);
                $permission->assignRole($SuperAdminrole); 
            }
        }
    }
}
