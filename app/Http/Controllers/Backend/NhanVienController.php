<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use App\Models\Project;
use App\Models\Notification;

class NhanVienController extends Controller
{
    public function index()
    {
        $nhansu = User::paginate(10); // Phân trang với 10 bản ghi mỗi trang
        return view('nhansu.nhansu', compact('nhansu'));
    }
    public function deleteEmploye(Request $request){
        $request->validate([
            'id' => 'required',
        ]);
        $new_user = Auth::user() ;
        $user_old = Project::where('userID', $request->id)->get();
        $nhanvien = User::find($request->id);
        
        

        
        if($user_old->isNotEmpty()){
            Project::where('userID', $request->id)
           ->update(['userID' => $new_user->id]);
           foreach ($user_old as $user_old) {
            $content = 'dự án '.$user_old->projectName .' không có người quản lý ';
            Notification::create([
                'user_id' => $new_user->id,
                'title' =>'Thay đổi giám sát',
                'content' => $content,
            ]);
        }
            
        }
        $content = 'Đã xoá nhân viên: '.$nhanvien->name;
        Notification::create([
            'user_id' => $new_user->id,
            'title' =>'Xoá nhân viên ',
            'content' => $content,
        ]);

        $nhanvien->delete();
        $notification = array(
            'message' => 'Nhân viên đã được xoá',
            'alert-type' => 'success'
        );
        return redirect()->route('nhansu')->with($notification);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'usercode' => ['required', 'string', 'max:255'],
            'role' =>'required',
            'position' =>'required',  
            'address'=>'required',
            'phone'=> 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usercode' => $request->usercode,
            'position' => $request->position,
            'role' => $request->role,
            'address'=> $request->address,
            'phone'=> $request->phone
        ]);
        $new_user = Auth::user();
        $content = 'Đã thêm nhân viên: '.$user->name;
        Notification::create([
            'user_id' => $new_user->id,
            'title' =>'Thêm nhân viên',
            'content' => $content,
        ]);

        $notification = array(
            'message' => 'Nhân viên đã được Thêm',
            'alert-type' => 'success'
        );
        
        return redirect()->route('nhansu')->with($notification);
    }
    public function search(Request $request){
        $query = $request->input('query');
        

        // Thực hiện truy vấn tìm kiếm trong cơ sở dữ liệu
        $nhansu = User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('usercode', 'LIKE', "%{$query}%")
                    ->paginate(7);
        
            
            return view('nhansu.nhansu', compact('nhansu','query'));
        if($query ===''){
            $nhansu = User::paginate(5); // Phân trang với 10 bản ghi mỗi trang
            return view('nhansu.nhansu', compact('nhansu'));
        }
    }
    public function editEmployee(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'usercode' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'address' => 'required|string',
            'phone' => 'required|string',
            'position' => 'required|string',
            'role' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update($validatedData);

        $new_user = Auth::user();
        $content = 'Đã chỉnh sửa nhân viên: '.$user->name;
        Notification::create([
            'user_id' => $new_user->id,
            'title' =>'chỉnh sửa nhân viên',
            'content' => $content,
        ]);

        $notification = array(
            'message' => 'Nhân viên đã được chỉnh sửa',
            'alert-type' => 'success'
        );
        
        return redirect()->route('nhansu')->with($notification);
    
    }
}
