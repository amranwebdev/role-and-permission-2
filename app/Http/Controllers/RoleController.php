<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
        //  $user = Auth::user();
        //  $role_id = $user->getPermissionsViaRoles();
        //  foreach ($role_id as  $iRole) {
        //     $roleId = $iRole->pivot->role_id;
        // }
        //  dd($roleId);

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

        
        

        // $role_list=[];
        // foreach($role_data->permissions as $role){
        //     $role_list[]=$role->name;
        // }
        // dd($role_list);
        


        $permission_groups = User::getPermissionGroups();
        foreach($permission_groups as $gName){
            $groupName = $gName->name;
            $role_permission = Permission::select('name')->where('group_name', $groupName)->get();
            foreach($role_permission as $pName){
                $permission_name[] = $pName->name;
            }
            $gName->permission_name = $permission_name;
        $permission_name=[];
        }
        // dd($permission_groups);


         //dd($permission_groups);

        //$permission = Permission::get();
        //$rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$request->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all();

        // $user = Auth::user();
        // $permissionNames = $user->getAllPermissions()->pluck('name');
        // dd($permissionNames);
        //dd($role_data);
        // $permission_groups = User::getPermissionGroups();
        // foreach($permission_groups as $permission_group){

        //     $permissions = DB::table('permissions')->select('name','id')->where('group_name',$permission_group->name)->get();

        // }

        // $all_permissions = DB::table('permissions')->get();
        // dd($all_permissions);
        


        if ($role_data) {
            return response()->json([
                'success' => true,
                'role'    => $role_data,
                'permissions'    => $permission_groups,
                // 'permission_groups' => $permission_groups,
                // 'role_list'    => $all_permissions,
                //'rolepermission'    => $rolePermissions,
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
