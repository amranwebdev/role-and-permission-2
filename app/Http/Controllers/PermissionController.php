<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function Index()
    {
        $Permission_list = Permission::all();
        $Permission_group = PermissionGroup::all();
        // dd($Permission_list);

        return view('admin.role_permission.permission', compact('Permission_list','Permission_group'));

    }

    public function Store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'        => 'required|max:100',
            'group_name'        => 'required|max:100',
        ]);

        if ($validator->fails()) {

            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {

            $pGroup = Permission::create([
                'name' => $request->name,
                // gurd name field is not so necessary thats why web gurd value sented default static value
                'guard_name' => 'web',
                'group_name' => $request->group_name,
            ]);
            //dd($pGroup);
            $data = array();
            $data['message'] = 'Permission Added Successfully';
            $data['name']  = $pGroup->name;
            $data['group_name']  = $pGroup->group_name;
            $data['id'] = $pGroup->id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }
 
    public function Edit(Request $request)
    {
        $pGroup = Permission::find($request->id);
        //dd($pGroup);
        if ($pGroup) {
            return response()->json([
                'success' => true,
                'data'    => $pGroup,
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
            'name'       => 'required|max:100',
            'group_name'        => 'required|max:100',

        ]);
        if ($validator->fails()) {
            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {
            $pGroup  = Permission::find($request->hidden_id);

            $pGroup['name']       = $request->name;
            $pGroup['group_name'] = $request->group_name;

            $pGroup->update();

            $data                = array();
            $data['message']     = 'Permission updated successfully';
            $data['name']    = $pGroup->name;
            $data['group_name']    = $pGroup->group_name;
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
        $pGroup = Permission::findOrFail($request->id);
        if ($pGroup) {
            $pGroup->delete();
            $data            = array();
            $data['message'] = 'Permission deleted successfully';
            $data['id']      = $request->id;
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } else {
            $data            = array();
            $data['message'] = 'Permission can not deleted!';
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        }
    }


}
