<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:blog-list|blog-create|blog-edit|blog-delete', ['only' => ['alltags']]);
         $this->middleware('permission:blog-create', ['only' => ['tagStore']]);
         $this->middleware('permission:blog-edit', ['only' => ['tagEdit','tagUpdated']]);
         $this->middleware('permission:blog-delete', ['only' => ['tagDestrotoy']]);
    }


    public function alltags()
    {
        //dd($car_info);
        $tag_list = Blog::all();
        return view('admin.blog.blog', compact('tag_list'));

    }

    public function tagStore(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'        => 'required|max:100',
        ]);

        if ($validator->fails()) {

            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {

            $idea = Blog::create([
                'name' => $request->name,

            ]);
            //dd($idea);
            $data = array();
            $data['message'] = 'Blog Added Successfully';
            $data['name']  = $idea->name;
            $data['id'] = $idea->id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }
 
    public function tagEdit(Request $request)
    {
        //dd($request->id);
        $dasset = Blog::find($request->id);
        //dd($car);
        if ($dasset) {
            return response()->json([
                'success' => true,
                'data'    => $dasset,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data'    => 'No information found',
            ]);
        }
    }

    public function tagUpdated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|max:100',
        ]);
        if ($validator->fails()) {
            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {
            $dasset  = Blog::find($request->hidden_id);

            $dasset['name']       = $request->name;
            $dasset->update();

            $data                = array();
            $data['message']     = 'Blog updated successfully';
            $data['name']       = $dasset->name;
            $data['id']          = $request->hidden_id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }

    public function tagDestrotoy(Request $request)
    {

        //dd($request->id);
        $idea = Blog::findOrFail($request->id);
        if ($idea) {
            $idea->delete();
            $data            = array();
            $data['message'] = 'Blog deleted successfully';
            $data['id']      = $request->id;
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } else {
            $data            = array();
            $data['message'] = 'Blog can not deleted!';
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        }
    }
}
