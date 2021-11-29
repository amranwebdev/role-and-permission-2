<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Amran Hossain', 
            'email' => 'amran@gmail.com',
            'password' => bcrypt('12345678')
        ]);
    
        // $role = Role::create(['name' => 'Super-Admin']);
     
        // $permissions = Permission::pluck('id','id')->all();
   
        // $role->syncPermissions($permissions);
     
        // $user->assignRole([$role->id]);
    }
}
