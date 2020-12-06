<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('{slug}',[\App\Http\Controllers\LoginController::class,'index']);
Route::get('/dashboard',[\App\Http\Controllers\LoginController::class,'dashboard']);
Route::get('/',[\App\Http\Controllers\LoginController::class,'index']);

//login và logout url phải có dạng name/other_name
Route::prefix('/app')->group(function (){
    //tags
    Route::post('/create_tag',[TagController::class,'create']);
    Route::get('/display_tag',[TagController::class,'index']);
    Route::post('/edit_tag',[TagController::class,'edit']);
    Route::post('/delete_tag',[TagController::class,'delete']);
    //categorty
    Route::post('/create_category',[\App\Http\Controllers\CategoryController::class,'create']);
    Route::get('/display_category',[\App\Http\Controllers\CategoryController::class,'index']);
    Route::post('/upload',[\App\Http\Controllers\CategoryController::class,'upload']);
    Route::post('/delete_image',[\App\Http\Controllers\CategoryController::class,'deleteImage']);
    Route::post('/delete_category',[\App\Http\Controllers\CategoryController::class,'deleteCategory']);
    Route::post('edit_category',[\App\Http\Controllers\CategoryController::class,'edit']);
    //AdminUser
    Route::post('/create_adminUser',[\App\Http\Controllers\AdminController::class,'create']);
    Route::get('/display',[\App\Http\Controllers\AdminController::class,'index']);
    Route::get('/display_role',[\App\Http\Controllers\AdminController::class,'role']);
    Route::post('/edit',[\App\Http\Controllers\AdminController::class,'edit']);
    Route::post('/delete',[\App\Http\Controllers\AdminController::class,'delete']);
    //logout
    Route::get('/logout',[\App\Http\Controllers\LoginController::class,'logout']);
    //login
    Route::post('/login',[\App\Http\Controllers\LoginController::class,'login']);
    //role
    Route::get('role',[\App\Http\Controllers\RoleController::class,'index']);
    Route::post('create_role',[\App\Http\Controllers\RoleController::class,'create']);
    Route::post('edit_role',[\App\Http\Controllers\RoleController::class,'edit']);
    Route::post('delete_role',[\App\Http\Controllers\RoleController::class,'delete']);
    //assign
    Route::post('assign',[\App\Http\Controllers\RoleController::class,'assign']);
});



