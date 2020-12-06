<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //display
    public function index(){
        return Role::orderBy('id')->get();
    }
    //create
    public function create(Request $request){
        $this->validate($request,
            [
                'roleName' => 'required'
            ],
            [
                'roleName.required' => 'Vui lòng nhập role name'
            ]
        );
        return Role::create([
            'roleName' => $request->roleName
        ]);
    }
    //edit
    public function edit(Request $request){
        $this->validate($request,
            [
                'roleName' => 'required'
            ],
            [
                'roleName.required' => 'Vui lòng nhập role name'
            ]
        );
        return Role::where('id',$request->id)->update([
            'roleName' => $request->roleName
        ]);
    }
    public function delete(Request $request){
        return Role::where('id',$request->id)->delete();
    }
    //assign
    public function assign(Request $request){
        return Role::where('id',$request->id)->update([
           'permission' => $request->permission
        ]);
    }
}
