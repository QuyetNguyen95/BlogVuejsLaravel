<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    //thêm mới
    public function create(Request $request){
        //validate data
        $this->validate($request,
            [
                'fullName'  => 'bail|required',
                'email'     => 'bail|required|email|unique:users,email',
                'password'  => 'bail|required|min:6',
            ],
            [
                'fullName.required'  => 'fullName không được để trống',
                'email.required'     => 'email không được để trống',
                'email.email'        => 'Bạn phải nhập đúng định dạng email',
                'email.unique'       => 'Email đã tồn tại',
                'password.required'  => 'password không được để trống',
                'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự',

            ]
        );

        //send data to mysql
        $pass = bcrypt($request->password);
        $data = [

        ];
        //tạo dữ liệu cho adminUser
       User::create([
            'fullName' => $request->fullName,
            'email'    => $request->email,
            'password' => $pass,
            'role_id' => $request->role
        ]);
        return User::with('role:id,roleName')->where('email',$request->email)->get();
    }
    //hiển thị
    public  function index(){
        return  User::with('role:id,roleName')->where('role_id','!=',2)->orderBy('id','desc')->get();
    }
    //update
    public function edit(Request  $request){
        //validate data
        $this->validate($request,
            [
                'fullName'  => 'bail|required',
                'email'     => 'bail|required|email|unique:users,email,'.$request->id,
                'password'  => 'bail|min:6',
            ],
            [
                'fullName.required'  => 'fullName không được để trống',
                'email.required'     => 'email không được để trống',
                'email.email'        => 'Bạn phải nhập đúng định dạng email',
                'email.unique'       => 'Email đã tồn tại',
                'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự',

            ]
        );
        $data = [
            'fullName'  => $request->fullName,
            'email'     => $request->email,
            'role_id'  => $request->role
        ];
        if (!empty($request->password)){
            $pass = bcrypt($request->password);
            $data['password'] = $pass;
        }
       User::where('id',$request->id)->update($data);
        return User::with('role:id,roleName')->where('email',$request->email)->get();
    }
    //xóa
    public function delete(Request $request){
        User::where('id',$request->id)->delete();
        return $request->id;
    }
    //hiển thị tên danh sách role
    public function role(Request $request){
        return  Role::select('id','roleName')->where('roleName','!=','User')->get();
    }
}
