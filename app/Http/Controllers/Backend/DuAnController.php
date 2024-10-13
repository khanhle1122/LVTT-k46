<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Document;
use App\Models\File;
use App\Models\Notification;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;




class DuAnController extends Controller
{
    public function generateUniqueFileName($directory, $originalName)
    {
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $counter = 1;

        // Tên file ban đầu
        $newFileName = $fileName . '.' . $extension;

        // Kiểm tra xem file đã tồn tại chưa
        while (Storage::exists('public/' . $directory . '/' . $newFileName)) {
            // Nếu đã tồn tại, thêm số vào sau tên file
            $newFileName = $fileName . ' (' . $counter . ').' . $extension;
            $counter++;
        }

        return $newFileName;
    }
    // show all dự án 
    public function viewDA(){
        $project= Project::paginate(10);
        return view('project.project',compact('project'));
    } 
    // thêm dự án 
    public function StoreProject(Request $request){
        $request->validate([
            'projectName' => 'required',
            'projectCode' => 'required', // Chỉnh lại thành 'projectCode'
            'description' => 'required',
            'startDate' => 'required | date',
            'endDate' => 'required | date',
            'clientName' => 'required',
            'level' => 'required',
            'budget' => 'required',
            'userID' => 'required|exists:users,id',
            'files.*' => 'required|file|max:2048', // Validation cho từng file

        ]);

        

        try{
            $budget = str_replace('.', '', $request->budget);

            // Lưu dự án vào database
            $project = Project::create([
                'projectName' => $request->projectName,
                'projectCode' => $request->projectCode, // Đúng tên biến 'projectCode'
                'description' => $request->description,
                'startDate' => $request->startDate,
                'endDate' => $request->endDate,
                'level' => $request->level,
                'budget' => $budget,
                'status' => 1,
                'progress'=>0 ,
                'clientName' => $request->clientName, // Đúng tên biến 'request'
                'userID' => $request->userID, // Đúng tên biến 'request'
            ]);
            
            
            // Thông báo thành công
            $new_user = Auth::user();
            $content = 'Đã thêm dự án '.$project->projectName.' mã '.$project->projectCode;
            Notification::create([
                'user_id' => $new_user->id,
                'title' =>'Thêm dự án',
                'content' => $content,
            ]);
            $documentName = $project->projectCode.'_'.$project->projectName;
            $document = Document::create([
                'documentName' => $documentName,
                'projectID' => $project->id,
            ]);
            $content = 'Đã thêm thư mục '.$project->projectName;
            Notification::create([
                'user_id' => $new_user->id,
                'title' =>'Thêm thư mục',
                'content' => $content,
            ]);

            if($request->hasFile('files')) {
                $request->validate([
                    'files.*' => 'required|file|max:2048',
                ]);
                $files = $request->file('files');
                $document_dir = 'uploads/' . $documentName;
                
                foreach ($files as $file) {
                    $originalName = $file->getClientOriginalName();
        
                    // Tạo tên file unique
                    $fileName = $this->generateUniqueFileName($document_dir, $originalName);
                    
                    // Lưu file và lấy đường dẫn đầy đủ
                    $filePath = $file->storeAs('public/' . $document_dir, $fileName);
                    File::create([
                        'fileName' => $fileName,
                        'filePath' => $filePath, // Lưu đường dẫn đầy đủ vào DB
                        'documentID' => $document->id,
                    ]);
                }
            }
            
            $notification = array(
                'message' => 'Dự án đã được cập nhật',
                'alert-type' => 'success'
            );
            
            return redirect()->route('project')->with($notification);
        }catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }

        
    }

    public function toggleStar($id = null)
    {
        $project = Project::find($id);

        if ($project) {
            // Đảo ngược trạng thái status giữa 0 và 1
            $project->status = $project->status == 1 ? 0 : 1;
            $project->save();

            
        }

        return redirect()->back();
    }


    

    // xoá dự án
    public function deleteProject(Request $request){
        $request->validate([
            'id' => 'required',
        ]);
        $project = Project::find($request->id);
        $document = Document::where('projectID',$request->id)->get();
        


        $content = 'Đã xoá dự án '.$project->projectName.'mã '.$project->projectCode;
        $new_user = Auth::user() ;
        Notification::create([
            'user_id' => $new_user->id,
            'title' =>'xoá dự án',
            'content' => $content,
        ]);
        foreach ($document as $document){
            
            
                // Xoá toàn bộ thư mục và các file bên trong
               
                $file = File::where('documentID', $document->id)->get();
                foreach($file as $file){
                    
                    // Kiểm tra nếu file tồn tại trong storage
                    
                        // Xoá file từ storage
                        $file->delete();
                    
                    
                }
                Storage::deleteDirectory('public/uploads/' . $document->documentName);
                $document->delete();
            
            
        }
        $project->delete();
        $notification = array(
            'message' => 'Dự án đã được xoá',
            'alert-type' => 'success'
        );
        
        return redirect()->route('project')->with($notification);
    }
    // tìm kiếm dự án
    public function search(Request $request){
        $query = $request->input('query');
        

        // Thực hiện truy vấn tìm kiếm trong cơ sở dữ liệu
        $project = Project::where('projectName', 'LIKE', "%{$query}%")
                    ->orWhere('projectCode', 'LIKE', "%{$query}%")
                    ->orWhere('startDate', 'LIKE', "%{$query}%")
                    ->orWhere('endDate', 'LIKE', "%{$query}%")
                    ->orWhere('clientName', 'LIKE', "%{$query}%")

                    ->paginate(10);
        
            
            return view('project.project', compact('project','query'));
        if($query ===''){
            $nhansu = User::paginate(10); // Phân trang với 10 bản ghi mỗi trang
            return view('project.project', compact('project'));
        }
    }

    // sửa dự án 
    public function editProject(Request $request){
        $validatedData = $request->validate([
            'projectName' => 'required|string|max:255',
            'projectCode' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'level' => 'required|string|max:255',
            'budget' => 'required|numeric',
            'clientName' => 'required|string|max:255',
        ]);
        $project = Project::findOrFail($request->projectID);
        $project->update($validatedData);
        $new_user = Auth::user();
        $content = 'Đã chỉnh sửa dự án: '.$project->name.'mã: '.$project->projectCode;
        Notification::create([
            'user_id' => $new_user->id,
            'title' =>'chỉnh sửa dự án',
            'content' => $content,
        ]);

        $notification = array(
            'message' => 'dự án đã được chỉnh sửa',
            'alert-type' => 'success'
        );
        
        return redirect()->route('project')->with($notification);
    }
    
}

