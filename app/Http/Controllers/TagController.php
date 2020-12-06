<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function create(Request $request)
    {
        // $this->validate($request,[
        //     'tagName' => 'required'
        // ]);
        return Tag::create([
            'tagName' => $request->tagName,
        ]);
    }

    public function index()
    {

        return Tag::orderBy('id', 'desc')->get();

    }

    public function edit(Request $request){
         Tag::where('id',$request->id)->update(['tagName' => $request->tagName]);
         return response()->json(['tagName'=>$request->tagName]);
    }

    public function delete(Request $request){
        return Tag::where('id',$request->id)->delete();
    }
}
