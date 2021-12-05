<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionGroupController extends Controller
{
    public function Index()
    {
        $PermissionGroup_list = PermissionGroup::all();
        // dd($PermissionGroup_list);

        return view('admin.role_permission.permission_group', compact('PermissionGroup_list'));

    }

    public function Store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'permassiongroup_name'        => 'required|max:100',
        ]);

        if ($validator->fails()) {

            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {

            $pGroup = PermissionGroup::create([
                'name' => $request->permassiongroup_name,
            ]);
            //dd($pGroup);
            $data = array();
            $data['message'] = 'Permission Group Added Successfully';
            $data['name']  = $pGroup->name;
            $data['id'] = $pGroup->id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }
 
    public function Edit(Request $request)
    {
        $pGroup = PermissionGroup::find($request->id);
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
            'permassiongroup_name'       => 'required|max:100',
        ]);
        if ($validator->fails()) {
            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {
            $pGroup  = PermissionGroup::find($request->hidden_id);

            $pGroup['name']       = $request->permassiongroup_name;
            $pGroup->update();

            $data                = array();
            $data['message']     = 'PermissionGroup updated successfully';
            $data['name']    = $pGroup->name;
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
        $pGroup = PermissionGroup::findOrFail($request->id);
        if ($pGroup) {
            $pGroup->delete();
            $data            = array();
            $data['message'] = 'PermissionGroup deleted successfully';
            $data['id']      = $request->id;
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } else {
            $data            = array();
            $data['message'] = 'PermissionGroup can not deleted!';
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        }
    }

}
