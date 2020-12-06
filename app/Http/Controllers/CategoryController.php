<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(Request $request){
        //validate theo laravel
       $this->validate(
           $request,
           [
               'categoryName' => 'required|max:255',
               'iconImage'    => 'required'
           ],
           [
               'categoryName.required' => 'Danh mục trống , làm ơn nhập...',
               'iconImage.required' => 'Ảnh trống , làm ơn nhập...',
               'categoryName.max' => 'Tên bạn nhập quá dài..'
           ]
       );
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage'    => $request->iconImage
        ]);
    }

    public  function index(){
        return Category::orderBy('id','desc')->get();
    }

    public  function upload(Request $request){
        $this->validate(
            $request,
            ['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'],
            [
                'file.required' => 'Hình ảnh trống , làm ơn nhập...',
                'file.image' => 'File bạn upload lên phải là ảnh',
                'file.mimes' => 'Đuôi file chưa đúng định dạng',
                'file.max'  => 'Kích thước file vướt quá 2M'
            ]
        );
        $picture = time().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads'),$picture);
        return $picture;
    }
    public  function deleteFileFromServer($fileName){
        $image_path = public_path().$fileName;
        if(file_exists($image_path)){
            unlink($image_path);
        }
    }
    public function deleteImage(Request  $request){
        //xóa ảnh trong folder public
        $this->deleteFileFromServer($request->imageName);
        return 'done';
    }
    public  function edit(Request $request){
        //validate theo laravel
        $this->validate(
            $request,
            [
                'categoryName' => 'required|max:255',
                'iconImage'    => 'required'
            ],
            [
                'categoryName.required' => 'Danh mục trống , làm ơn nhập...',
                'iconImage.required' => 'Ảnh trống , làm ơn nhập...',
                'categoryName.max' => 'Tên bạn nhập quá dài..'
            ]
        );
        return Category::where('id',$request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage'    => $request->iconImage
        ]);
    }
    public function deleteCategory(Request  $request){
        //xóa ảnh trong folder uploads
        $this->deleteFileFromServer($request->iconImage);
        //xóa tên ảnh trên server
        Category::where('id',$request->id)->delete();
        return $request->id;
    }
}
