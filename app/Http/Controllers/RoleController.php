<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use DB;

class RoleController extends Controller
{
    public function Index()
    {
        //dd($car_info);
        $role_list = Role::all();
        $permission_list = Permission::get();
        $permission_groups = User::getPermissionGroups();
         //dd($permission_groups);

        return view('admin.role_permission.roles', compact('role_list','permission_list','permission_groups'));
        //This one created for roles.blade.php
        //$permissions = App\Models\User::getPermissionGroups($group->name);
        

    }

    public function Store(Request $request)
    {
        //dd($request->all());

        $validator = Validator::make($request->all(), [

            'role'        => 'required|max:100',
            // 'permission'   => 'required|max:2000',
        ]);

        if ($validator->fails()) {

            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {

            $role_data = Role::create(['name' => $request->role]);
            $role_data->syncPermissions($request->input('permission'));

            //dd($role_data);
            $data = array();
            $data['message'] = 'Roles Added Successfully';
            $data['role']  = $role_data->name;
            //$data['idea_description']  = $role_data->permission;
            $data['id'] = $role_data->id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }
 
    public function Edit(Request $request)
    {
        //dd($request->id);
        $role_data = Role::find($request->id);
        $role_data->permissions->pluck('name');

        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$request->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all();

        //dd($role_data);
        if ($role_data) {
            return response()->json([
                'success' => true,
                'role'    => $role_data,
                'permission'    => $permission,
                'rolepermission'    => $rolePermissions,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data'    => 'No information found',
            ]);
        }
    }

    public function Update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role'       => 'required|max:100',
            'permission'       => 'required|max:2000',
        ]);
        if ($validator->fails()) {
            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {
            $role_data  = Role::find($request->hidden_id);

            $role_data['role']       = $request->role;
            $role_data['permission']       = $request->permission;
            $role_data->update();

            $data                = array();
            $data['message']     = 'Roles updated successfully';
            $data['role']       = $role_data->role;
            $data['idea_description']       = $role_data->permission;
            $data['id']          = $request->hidden_id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }

    public function Destroy(Request $request)
    {

        //dd($request->id);
        $role_data = Role::findOrFail($request->id);
        if ($role_data) {
            $role_data->delete();
            $data            = array();
            $data['message'] = 'Roles deleted successfully';
            $data['id']      = $request->id;
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } else {
            $data            = array();
            $data['message'] = 'Roles can not deleted!';
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        }
    }


}
