<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    //tiến hành kiểm tra redirect page
    public function index(Request $request){
        //chưa login và url không phải là login page
        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }
        //chưa login  và đang ở login page
        if (!Auth::check() && $request->path() == 'login') {

            return view('welcome');
        }
        //đã login
        //kiểm tra nếu là user thì không được đăng nhập redirect về trang login
        $user = Auth::user();
        if ($user->role->isAdmin !== 0) {
            return redirect('/login');
        }
        //đã login và đang ở trang login page
        if ($request->path() == 'login') {
            return redirect('/dashboard');
        }
        return $this->checkForPermission($user,$request);
        //=return view('notfound');
        //đã login và url không phải là login page
     //  return view('welcome');
    }
    //check permission
    public function checkForPermission($user,$request){
        $permission = json_decode( $user->role->permission);
        $hasPermission = false;
        foreach ($permission as $p){
             if($p->name == $request->path()){
                 if ($p->read){
                    $hasPermission = true;
                 }
             }
        }
        if ($hasPermission){
             return view('welcome');
        }
        return view('notfound');
    }
    public function dashboard(){
        return view('welcome');
    }
    public function login(Request $request){
        $this->validate($request,
            [
                'email'     => 'bail|required|email',
                'password'  => 'bail|min:6',
            ],
            [
                'email.required'     => 'email không được để trống',
                'email.email'        => 'Bạn phải nhập đúng định dạng email',
                'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự',

            ]
        );
        $data = [
            'email'     => $request->email,
            'password'  => $request->password
        ];
        if(Auth::attempt( $data)){
            //kiểm tra có phải là user hay không, nếu là user thì logout và trả vè lỗi
            $user = Auth::user();
            if ($user->role->isAdmin !== 0){
                Auth::logout();
                return response()->json([
                    'msg' => 'Đăng nhập thất bại'
                ],401);
            }
            return response()->json([
                'msg'  => 'Đăng nhập thành công',
                'user' => $user
            ]);
        }else{
            return response()->json([
                'msg' => 'Đăng nhập thất bại'
            ],401);
        }
    }
    public function logout(){
        Auth::logout();
        return response()->json([
            'code' => 1
        ]);
    }
}
